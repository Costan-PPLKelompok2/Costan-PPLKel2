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

    public function pemilik()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function favoriters()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }
    }