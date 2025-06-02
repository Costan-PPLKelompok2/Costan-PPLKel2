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
        'durasi_sewa', // Kolom ini sudah ada
    ];

    /**
     * Get the user that owns the Kos.
     */
    public function pemilik()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The users that have favorited the Kos.
     */
    public function favoriters()
    {
        return $this->belongsToMany(User::class, 'favorites', 'kos_id', 'user_id')->withTimestamps();
    }

    // Accessor untuk kolom 'durasi_sewa'
    // Jika di database Anda menyimpan angka (misal: 1, 12)
    public function getDurasiSewaAttribute($value)
    {
        switch ($value) {
            case 'bulanan': // Jika di DB sudah string 'bulanan'
                return 'Bulanan';
            case 'tahunan': // Jika di DB sudah string 'tahunan'
                return 'Tahunan';
            case 'mingguan': // Jika di DB sudah string 'mingguan'
                return 'Mingguan';
            default:
                return 'Tidak Tersedia'; // Atau nilai default lainnya
        }
    }

        public function reviews()
    {
        return $this->hasMany(\App\Models\Review::class);
    }
}