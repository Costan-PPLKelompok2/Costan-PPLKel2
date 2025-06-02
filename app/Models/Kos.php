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
        'jenis_kos',
        'durasi_sewa',
        'status_ketersediaan',
        'views',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'status_ketersediaan' => 'boolean',
    ];

    /**
     * Relasi ke pemilik kos (User)
     */
    public function pemilik()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke review kos
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Relasi ke chat rooms
     */
    public function chatRooms()
    {
        return $this->hasMany(ChatRoom::class);
    }

    /**
     * Relasi ke users yang memfavoritkan kos ini
     */
    public function favoriters()
    {
        return $this->belongsToMany(User::class, 'favorites', 'kos_id', 'user_id')->withTimestamps();
    }

    /**
     * Accessor untuk menampilkan label durasi sewa
     */
    public function getDurasiSewaAttribute($value)
    {
        switch ($value) {
            case 'bulanan':
                return 'Bulanan';
            case 'tahunan':
                return 'Tahunan';
            case 'mingguan':
                return 'Mingguan';
            default:
                return 'Tidak Tersedia';
        }
    }
}
