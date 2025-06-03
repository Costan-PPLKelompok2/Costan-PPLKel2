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
        'status_ketersediaan',
        'views',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'status_ketersediaan' => 'boolean',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
  
    public function pemilik()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user() // Ganti 'pemilik' dengan 'user' jika ini yang benar
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function chatRooms()
    {
        return $this->hasMany(ChatRoom::class);
    }

    public function favoriters()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

}
