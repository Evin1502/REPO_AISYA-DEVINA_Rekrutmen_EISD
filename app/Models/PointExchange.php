<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointExchange extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'user_id',
        'reward_id',
        'reward_type',
        'reward_name',
        'value',
        'points_used',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reward()
    {
        return $this->belongsTo(Reward::class);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isSaldo(): bool
    {
        return $this->reward_type === Reward::CATEGORY_SALDO;
    }

    public function isBarang(): bool
    {
        return $this->reward_type === Reward::CATEGORY_BARANG;
    }

    public function typeLabel(): string
    {
        return $this->isSaldo() ? 'Saldo / Cash Balance' : 'Barang';
    }

    /**
     * Nilai rupiah yang ditampilkan; untuk barang kembalikan nama reward.
     */
    public function displayValue(): string
    {
        if ($this->isSaldo()) {
            return 'Rp'.number_format((float) $this->value, 0, ',', '.');
        }

        return $this->reward_name ?? '-';
    }
}
