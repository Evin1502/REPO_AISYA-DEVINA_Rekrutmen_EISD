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
        ];
    }

    /* ---------------------------------------------------------------
     |  Role helper (dipakai di Blade & Middleware)
     |---------------------------------------------------------------*/
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

    /* ---------------------------------------------------------------
     |  Relasi 1-to-Many
     |---------------------------------------------------------------*/

    /** Sebagai Resident: pengajuan yang dia buat sendiri. */
    public function pickupRequests()
    {
        return $this->hasMany(PickupRequest::class, 'user_id');
    }

    /** Sebagai Collector: pengajuan yang ditugaskan ke dia. */
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

    /** Sebagai Admin: berita yang dia tulis. */
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
