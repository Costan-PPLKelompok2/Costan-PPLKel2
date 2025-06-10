<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'phone',
        'address',
        'profile_photo_path',
        'search_preferences',
        'price',
        'preferred_location',
        'preferred_kos_type',
        'preferred_facilities',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'search_preferences' => 'array',
        'preferred_facilities' => 'array',
    ];

    // Relasi favorit kos (penting!)
    public function favoriteKos(): BelongsToMany
    {
        return $this->belongsToMany(Kos::class, 'favorites')->withTimestamps();
    }

    public function chatRoomsAsTenant()
    {
        return $this->hasMany(ChatRoom::class, 'tenant_id');
    }

    public function chatRoomsAsOwner()
    {
        return $this->hasMany(ChatRoom::class, 'owner_id');
    }

    public function messagesSent()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    // Menggabungkan semua chat room dimana user terlibat
    public function allChatRooms()
    {
        return ChatRoom::where('tenant_id', $this->id)
                    ->orWhere('owner_id', $this->id);
    }

    // User sebagai pemilik
    public function reviewsDiterima()
    {
        return $this->hasMany(OwnerReview::class, 'pemilik_id');
    }

    // User sebagai penyewa
    public function reviewsDiberikan()
    {
        return $this->hasMany(OwnerReview::class, 'penyewa_id');
    }

    public function kos()
{
    return $this->hasMany(Kos::class, 'user_id');
}

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isPemilik()
    {
        return $this->role === 'pemilik';
    }

    public function isPenyewa()
    {
        return $this->role === 'penyewa';
    }
}
