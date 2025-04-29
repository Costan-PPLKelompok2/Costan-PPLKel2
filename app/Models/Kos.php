<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kos extends Model
{
    protected $table = 'kos';
    protected $primaryKey = 'id_kos';
    protected $fillable = [
        'nama_kos',
        'alamat',
        'harga',
        'fasilitas',
        'deskripsi',
        'status_ketersediaan',
    ];
}
