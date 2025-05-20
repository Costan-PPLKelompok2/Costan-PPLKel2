<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kos;
use App\Models\User;

class KosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @group KosSeeder
     */
    public function run(): void
    {
        // pastikan minimal ada 1 user
        $user = User::first();

        if (!$user) {
            $this->command->warn("No users found. Creating a dummy user first.");
            $user = User::factory()->create(); // jika menggunakan factory
        }

        // Buat beberapa data kos
        for ($i = 1; $i <= 5; $i++) {
            Kos::create([
                'user_id' => $user->id,
                'nama_kos' => "Kos Nyaman No. $i",
                'deskripsi' => "Deskripsi lengkap untuk kos ke-$i.",
                'alamat' => "Jalan Kos $i, Yogyakarta",
                'harga' => rand(500000, 1500000),
                'fasilitas' => "WiFi, Kamar Mandi Dalam, Lemari, Kasur",
                'foto' => 'banner-03.jpg',
                'status_ketersediaan' => rand(0, 1),
                'views' => rand(0, 100),
            ]);
        }
    }
}
