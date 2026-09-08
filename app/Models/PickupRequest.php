<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PickupRequest extends Model
{
    use HasFactory;

    public const TIME_SLOTS = [
        ['key' => '08:00-10:00', 'label' => '08.00 – 10.00', 'start' => '08:00', 'end' => '10:00'],
        ['key' => '10:00-12:00', 'label' => '10.00 – 12.00', 'start' => '10:00', 'end' => '12:00'],
        ['key' => '13:00-15:00', 'label' => '13.00 – 15.00', 'start' => '13:00', 'end' => '15:00'],
        ['key' => '15:00-17:00', 'label' => '15.00 – 17.00', 'start' => '15:00', 'end' => '17:00'],
    ];

    public const STATUS_LABELS = [
        'pending' => 'Menunggu Diproses',
        'approved' => 'Ditugaskan',
        'scheduled' => 'Dijadwalkan',
        'collected' => 'Selesai',
        'rejected' => 'Ditolak',
    ];

    public const MAX_REQUESTS_PER_SLOT = 3;

    public const SLOT_WINDOW_DAYS = 30;

    public const ACTIVE_STATUSES = ['pending', 'approved', 'scheduled'];

    protected $fillable = [
        'user_id',
        'collector_id',
        'address',
        'area',
        'status',
        'scheduled_at',
        'time_slot',
        'total_weight',
        'total_points',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
        ];
    }

    public function statusLabel(): string
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst((string) $this->status);
    }

    public function timeSlotLabel(): ?string
    {
        if (! $this->time_slot) {
            return null;
        }

        return self::slotByKey($this->time_slot)['label'] ?? $this->time_slot;
    }

    public static function timeSlots(): array
    {
        return self::TIME_SLOTS;
    }

    public static function slotByKey(string $key): ?array
    {
        foreach (self::TIME_SLOTS as $slot) {
            if ($slot['key'] === $key) {
                return $slot;
            }
        }

        return null;
    }

    public static function availabilityMap(int $days = self::SLOT_WINDOW_DAYS): array
    {
        $start = today();
        $end = today()->addDays($days - 1);

        $counts = self::query()
            ->selectRaw('DATE(scheduled_at) AS slot_date, time_slot, COUNT(*) AS total')
            ->whereIn('status', self::ACTIVE_STATUSES)
            ->whereNotNull('time_slot')
            ->whereBetween('scheduled_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->groupByRaw('DATE(scheduled_at), time_slot')
            ->get()
            ->groupBy('slot_date')
            ->map(fn ($rows) => $rows->pluck('total', 'time_slot')->map(fn ($total) => (int) $total));

        $map = [];
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            foreach (self::TIME_SLOTS as $slot) {
                $booked = (int) ($counts[$date->toDateString()][$slot['key']] ?? 0);
                $slotEnd = $date->copy()->setTimeFromTimeString($slot['end']);

                $map[$date->toDateString()][$slot['key']] = $booked < self::MAX_REQUESTS_PER_SLOT && $slotEnd->gt(now());
            }
        }

        return $map;
    }

    public static function isSlotAvailable(string $date, string $slotKey): bool
    {
        $slot = self::slotByKey($slotKey);

        if (! $slot) {
            return false;
        }

        $pickupDate = Carbon::parse($date);
        $slotEnd = $pickupDate->copy()->setTimeFromTimeString($slot['end']);

        if ($slotEnd->isPast()) {
            return false;
        }

        $booked = self::query()
            ->whereDate('scheduled_at', $pickupDate->toDateString())
            ->where('time_slot', $slotKey)
            ->whereIn('status', self::ACTIVE_STATUSES)
            ->count();

        return $booked < self::MAX_REQUESTS_PER_SLOT;
    }

    public function resident()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function collector()
    {
        return $this->belongsTo(User::class, 'collector_id');
    }

    public function pointHistories()
    {
        return $this->hasMany(PointHistory::class);
    }

    public function wasteCategories()
    {
        return $this->belongsToMany(
            WasteCategory::class,
            'pickup_request_waste_category',
            'pickup_request_id',
            'waste_category_id'
        )->withPivot(['estimated_weight', 'actual_weight'])
            ->withTimestamps();
    }
}
