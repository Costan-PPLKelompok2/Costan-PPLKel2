<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * Atribut yang bisa diisi massal
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Atribut yang disembunyikan
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * Atribut yang di-cast otomatis
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Atribut tambahan saat dikonversi ke array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Relasi: kos yang difavoritkan oleh user (penyewa)
     */
    public function favoriteKos()
    {
        return $this->belongsToMany(Kos::class, 'favorites', 'user_id', 'kos_id')->withTimestamps();
    }
}
