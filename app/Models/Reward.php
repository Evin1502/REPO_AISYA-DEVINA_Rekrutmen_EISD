<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;

    public const CATEGORY_SALDO = 'saldo';

    public const CATEGORY_BARANG = 'barang';

    protected $fillable = [
        'name',
        'category',
        'nominal',
        'description',
        'points_required',
        'stock',
        'image',
    ];

    protected function casts(): array
    {
        return [
            'nominal' => 'decimal:2',
        ];
    }

    public function pointExchanges()
    {
        return $this->hasMany(PointExchange::class);
    }

    public function isSaldo(): bool
    {
        return $this->category === self::CATEGORY_SALDO;
    }

    public function isBarang(): bool
    {
        return $this->category === self::CATEGORY_BARANG;
    }

    public function categoryLabel(): string
    {
        return $this->isSaldo() ? 'Saldo / Cash Balance' : 'Barang';
    }

    public function displayValue(): string
    {
        if ($this->isSaldo()) {
            return 'Rp'.number_format((float) $this->nominal, 0, ',', '.');
        }

        return $this->name;
    }
}
