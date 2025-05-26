<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FavoritKosDiubah extends Notification
{
    private $kos;

    public function __construct($kos)
    {
        $this->kos = $kos;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'judul_kos' => $this->kos->judul,
            'harga_baru' => $this->kos->harga,
            'link' => url('/kos/'.$this->kos->id)
        ];
    }
}
