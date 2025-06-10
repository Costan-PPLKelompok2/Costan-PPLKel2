<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kos;
use Illuminate\Support\Str;
use App\Models\User;


class KosSeeder extends Seeder
{
    public function run(): void
    {
        $id_pemilik = User::where('role', 'pemilik')->pluck('id')->toArray();

        $fotoList = [
            'foto_kos/oXBEAeue0fwronT9AtamzKmL9UaKiqGNyfUqeXnB.jpg',
            'foto_kos/TtNoextQJizdIyI2ajaktvyPYPn0KuSTbe2NJjlu.png',
            'foto_kos/xVxFfGj1fASqjevkXmtSBu5Kunjsm7E4KM8JvmS7.jpg',
        ];

        $fasilitasList = [
            'WiFi,Kasur,Lemari',
            'WiFi,AC,Kamar Mandi Dalam',
            'Kipas Angin,Meja Belajar,CCTV',
            'AC,Lemari,Parkir Motor',
        ];

        for ($i = 1; $i <= 50; $i++) {
            Kos::create([
                'user_id' => $id_pemilik[array_rand($id_pemilik)],
                'nama_kos' => 'Kos ' . Str::random(5),
                'deskripsi' => 'Kos nyaman dan aman dengan fasilitas lengkap.',
                'alamat' => 'Jl. Contoh No.' . rand(10, 100) . ', Kota X',
                'harga' => rand(500000, 2000000),
                'fasilitas' => $fasilitasList[array_rand($fasilitasList)],
                'foto' => $fotoList[array_rand($fotoList)],
                'status_ketersediaan' => (bool)rand(0, 1),
                'views' => rand(0, 1000),
                'latitude' => -7.0 + (rand(0, 1000) / 1000),
                'longitude' => 110.0 + (rand(0, 1000) / 1000),
            ]);
        }
    }
}
