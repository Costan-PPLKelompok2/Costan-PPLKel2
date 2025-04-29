<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KosSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID'); // pakai lokal Indonesia

        for ($i = 0; $i < 20; $i++) {
            DB::table('kos')->insert([
                'nama' => 'Kos ' . $faker->lastName,
                'alamat' => $faker->address,
                'harga' => $faker->numberBetween(500000, 2000000),
                'fasilitas' => json_encode([
                    'WiFi', 
                    'AC', 
                    'Kamar Mandi Dalam', 
                    'Parkir Motor'
                ]),
                'foto' => 'default.jpg', // kamu bisa ganti dengan foto beneran
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
