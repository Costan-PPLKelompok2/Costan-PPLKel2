<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Message;
use App\Models\Kost;
use App\Models\User_profile;


class KostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah ada user
        $users_profile = User_profile::first();

        // Jika tidak ada user, buat dummy user
        if (!$users_profile) {
            $users_profile = User_profile::create([
                'name' => 'Pemilik Kos Default',
                'email' => 'pemilik@example.com',
            ]);
        }

        // Buat data kos
        Kost::create([
            'owner_id' => $users_profile->id,
            'name' => 'Kos Mawar',
            'description' => 'Kos nyaman, dekat kampus dan aman.',
            'price' => 1200000.00,
            'address' => 'Jl. Mawar No. 10',
        ]);

        Kost::create([
            'owner_id' => $users_profile->id,
            'name' => 'Kos Melati',
            'description' => 'Kos eksklusif dengan fasilitas lengkap.',
            'price' => 1500000.00,
            'address' => 'Jl. Melati No. 5',
        ]);
    }
}
