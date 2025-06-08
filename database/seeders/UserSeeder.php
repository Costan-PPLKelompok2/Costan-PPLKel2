<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;  // <=== ini harus ada
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => "Test User",
            'email' => 'test@gmail.com',
            'password' => Hash::make('123123123'),
            'role' => 'penyewa',  // sesuaikan enum di DB
        ]);
            DB::table('users')->insert([
            'name' => 'Pemilik Kos 1',
            'email' => 'pemilik1@example.com',
            'password' => Hash::make('password'),
            'role' => 'pemilik',
        ]);
            DB::table('users')->insert([
            'name' => 'Pemilik Key',
            'email' => 'pekey@example.com',
            'password' => Hash::make('password'),
            'role' => 'pemilik',
    ]);
    }
}
