<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => "Test User",
            'email' => 'test@gmail.com',
            'password' => Hash::make('123123123'),
        ]);

        DB::table('users')->insert([
            'name' => "Pemilik Kos",
            'email' => 'pemilik@gmail.com',
            'password' => Hash::make('123123123'),
            'role' => 'pemilik',
        ]);
    }
}
