<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManajemenProfilPengguna extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'manajemen_profil_pengguna'; // karena nama tabel tidak sesuai default

    protected $fillable = [
        'nama',
        'nomor_telepon',
        'preferensi_pencarian',
        'foto_profil',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Mutator untuk mendapatkan url foto profil
    public function getFotoProfilUrlAttribute()
    {
        return asset('storage/' . $this->foto_profil);
    }
}
