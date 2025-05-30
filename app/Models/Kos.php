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
}
