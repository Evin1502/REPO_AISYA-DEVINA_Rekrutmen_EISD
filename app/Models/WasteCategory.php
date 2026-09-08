<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'points_per_kg',
    ];

    public function pickupRequests()
    {
        return $this->belongsToMany(
            PickupRequest::class,
            'pickup_request_waste_category',
            'waste_category_id',
            'pickup_request_id'
        )->withPivot(['estimated_weight', 'actual_weight'])
         ->withTimestamps();
    }
}
