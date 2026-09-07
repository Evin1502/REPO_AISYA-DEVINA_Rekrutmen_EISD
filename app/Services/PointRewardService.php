<?php

namespace App\Services;

use App\Exceptions\PointExchangeException;
use App\Models\PointExchange;
use App\Models\Reward;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Business Logic modul Point Reward.
 *
 * Alur yang dibakukan:
 *  1. Redeem — validasi kecukupan poin & stok, potong poin + stok,
 *     simpan transaksi (Redemption History Log) + mutasi PointHistory(type=redeem).
 *     Reward tipe SALDO langsung disetujui otomatis dan cash_balance dikredit
 *     seketika (tanpa persetujuan admin). Reward tipe BARANG tetap 'pending'.
 *  2. Approve — hanya status pending (barang); ubah status; jika reward saldo,
 *     kredit cash_balance pengguna (simulasi, tanpa payment gateway).
 *  3. Reject  — hanya status pending (barang); kembalikan (refund) poin,
 *     pulihkan stok, catat PointHistory(type=refund).
 */
class PointRewardService
{
    public function redeem(User $user, Reward $reward): PointExchange
    {
        if ($user->points < $reward->points_required) {
            throw new PointExchangeException('Poin kamu tidak cukup untuk menukar reward ini.');
        }

        if ($reward->stock < 1) {
            throw new PointExchangeException('Stok reward ini sudah habis.');
        }

        return DB::transaction(function () use ($user, $reward) {
            $user->decrement('points', $reward->points_required);
            $reward->decrement('stock');

            // Saldo otomatis langsung 'approved' + kredit cash_balance tanpa persetujuan admin.
            // Barang tetap 'pending' menunggu proses Admin.
            $isSaldo = $reward->isSaldo();

            $exchange = $user->pointExchanges()->create([
                'reward_id' => $reward->id,
                'reward_type' => $reward->category,
                'reward_name' => $reward->name,
                'value' => $isSaldo ? $reward->nominal : null,
                'points_used' => $reward->points_required,
                'status' => $isSaldo ? PointExchange::STATUS_APPROVED : PointExchange::STATUS_PENDING,
            ]);

            if ($isSaldo) {
                $user->increment('cash_balance', $reward->nominal);
            }

            $user->pointHistories()->create([
                'points' => -$reward->points_required,
                'type' => 'redeem',
                'description' => 'Penukaran poin untuk reward: '.$reward->name,
            ]);

            return $exchange;
        });
    }

    /**
     * Poin & stok sudah dipotong saat resident menukar, jadi approve hanya
     * mengubah status. Untuk reward saldo, kredit cash_balance pengguna.
     */
    public function approve(PointExchange $exchange): void
    {
        if (! $exchange->isPending()) {
            throw new PointExchangeException('Hanya penukaran berstatus pending yang bisa disetujui.');
        }

        DB::transaction(function () use ($exchange) {
            $exchange->update(['status' => PointExchange::STATUS_APPROVED]);

            if ($exchange->isSaldo()) {
                $exchange->user()->increment('cash_balance', $exchange->value);
            }
        });
    }

    /**
     * Kembalikan (refund) poin ke resident dan pulihkan stok reward,
     * lalu catat PointHistory(type=refund) dalam satu transaksi DB.
     */
    public function reject(PointExchange $exchange): void
    {
        if (! $exchange->isPending()) {
            throw new PointExchangeException('Hanya penukaran berstatus pending yang bisa ditolak.');
        }

        DB::transaction(function () use ($exchange) {
            $exchange->update(['status' => PointExchange::STATUS_REJECTED]);

            $exchange->user()->increment('points', $exchange->points_used);
            $exchange->reward()->increment('stock');

            $exchange->user->pointHistories()->create([
                'points' => $exchange->points_used,
                'type' => 'refund',
                'description' => 'Refund poin karena penukaran reward "'.($exchange->reward_name ?? '-').'" ditolak.',
            ]);
        });
    }
}
