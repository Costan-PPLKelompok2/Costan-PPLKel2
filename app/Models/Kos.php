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
    ];

    /**
     * Cast status_ketersediaan into boolean (1 = available, 0 = full)
     */
    protected $casts = [
        'status_ketersediaan' => 'boolean',
    ];

    /**
     * Pemilik kos (user relationship)
     */
    public function pemilik()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
