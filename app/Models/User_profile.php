<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_profile extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan
    protected $table = 'users_profile';

    // Kolom yang bisa diisi (mass assignment)
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'price_range',
        'location',
        'room_type',
        'facilities',
        'photo',
    ];
}
