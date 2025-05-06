<?php

namespace Database\Seeders;

use App\Models\Kos;
use App\Models\User;
use Illuminate\Database\Seeder;

class KosSeeder extends Seeder
{
    public function run()
    {
        // Pastikan pengguna dengan ID 1 ada
        $user = User::firstOrCreate([
            'email' => 'kayla@example.com',
        ], [
            'name' => 'Kayla Dummy',
            'password' => bcrypt('password')
        ]);

        // Sekarang kita bisa menambahkan data kos
        Kos::create([
            'user_id' => $user->id,
            'name' => 'Kos Dummy Kayla',
            'alamat' => 'Jl. Testing No. 123',
            'harga' => 500000,
            'fasilitas' => 'AC, Wifi',
        ]);
    }
}

