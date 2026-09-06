<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PickupRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'collector_id',
        'address',
        'status',
        'scheduled_at',
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

    /* ---------------------------------------------------------------
     |  Relasi 1-to-Many (inverse: belongsTo)
     |---------------------------------------------------------------*/

    /** Resident pemilik pengajuan. */
    public function resident()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Collector yang ditugaskan (nullable). */
    public function collector()
    {
        return $this->belongsTo(User::class, 'collector_id');
    }

    public function pointHistories()
    {
        return $this->hasMany(PointHistory::class);
    }

    /* ---------------------------------------------------------------
     |  Relasi Many-to-Many (WAJIB) - "Memilih kategori sampah"
     |---------------------------------------------------------------*/
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
