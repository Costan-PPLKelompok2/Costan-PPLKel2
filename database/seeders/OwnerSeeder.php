<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah sudah ada user owner, kalau belum buat 3 owner
        $existingOwner = User::where('role', 'pemilik')->first();

        if (!$existingOwner) {
            User::factory()->count(3)->create([
                'role' => 'pemilik', // pastikan ini ada di migration users table
            ]);
            $this->command->info("3 Owner users created.");
        } else {
            $this->command->info("Owner users already exist.");
        }
    }
}
