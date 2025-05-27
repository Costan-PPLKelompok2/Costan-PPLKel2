<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kos extends Model
{
    use HasFactory;

    protected $table = 'kos';

    protected $fillable = [
        'user_id',
        'nama_kos',
        'deskripsi',
        'alamat',
        'harga',
        'fasilitas',
        'foto',
    ];

    /**
     * Relasi ke pemilik kos (user)
     */
    public function pemilik()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke semua user yang memfavoritkan kos ini
     */
    public function favoriters()
    {
        return $this->belongsToMany(User::class, 'favorites', 'kos_id', 'user_id')->withTimestamps();
    }
}
