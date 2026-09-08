<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'points',
        'cash_balance',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'cash_balance' => 'decimal:2',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isResident(): bool
    {
        return $this->role === 'resident';
    }

    public function isCollector(): bool
    {
        return $this->role === 'collector';
    }

    public function cashBalanceLabel(): string
    {
        return 'Rp'.number_format((float) $this->cash_balance, 0, ',', '.');
    }

    public function pickupRequests()
    {
        return $this->hasMany(PickupRequest::class, 'user_id');
    }

    public function assignedPickups()
    {
        return $this->hasMany(PickupRequest::class, 'collector_id');
    }

    public function pointHistories()
    {
        return $this->hasMany(PointHistory::class);
    }

    public function pointExchanges()
    {
        return $this->hasMany(PointExchange::class);
    }

    public function news()
    {
        return $this->hasMany(News::class, 'author_id');
    }

    public function notifications()
    {
        return $this->hasMany(AppNotification::class, 'user_id');
    }

    public function unreadNotificationsCount(): int
    {
        return $this->notifications()->where('is_read', false)->count();
    }
}
