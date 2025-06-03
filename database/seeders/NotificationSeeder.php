<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\DatabaseNotification;
use Carbon\Carbon;
use App\Models\User;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil 1 user pertama (pastikan sudah ada user!)
        $user = User::first();

        if (!$user) {
            $this->command->error('Seeder gagal: tidak ada user ditemukan di database.');
            return;
        }

        for ($i = 1; $i <= 5; $i++) {
            DatabaseNotification::create([
                'id' => Str::uuid(),
                'type' => 'App\Notifications\NewMessageNotification',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $user->id,
                'data' => [
                    'chat_room_id' => rand(1, 10),
                    'sender_id' => rand(2, 5),
                    'sender_name' => 'Pengirim ' . $i,
                    'message' => 'Pesan contoh ke-' . $i,
                    'sent_at' => Carbon::now()->subMinutes($i * 3)->toDateTimeString(),
                ],
                'created_at' => now()->subMinutes($i * 3),
                'updated_at' => now()->subMinutes($i * 3),
            ]);
        }

        $this->command->info('Notifikasi dummy berhasil dibuat untuk user: ' . $user->email);
    }
}
