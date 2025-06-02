<?php

// app/Observers/KosObserver.php
namespace App\Observers;

use App\Models\Kos;
use App\Notifications\KosUpdatedNotification;

class KosObserver
{
    public function updating(Kos $kos)
    {
        $changes = [];

        if ($kos->isDirty('harga')) {
            $changes[] = 'harga';
        }

        if ($kos->isDirty('status_ketersediaan')) {
            $changes[] = 'status ketersediaan';
        }

        // Jika tidak ada perubahan yang kita pantau, keluar
        if (count($changes) === 0) {
            return;
        }

        // Kirim notifikasi ke semua penyewa yang favoritkan kos ini
        foreach ($kos->favoriters as $user) {
            $user->notify(new KosUpdatedNotification($kos, $changes));
        }
    }
}
