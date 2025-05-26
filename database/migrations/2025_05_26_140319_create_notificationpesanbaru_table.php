<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PesanBaruDariPemilik extends Notification
{
    private $pesan;

    public function __construct($pesan)
    {
        $this->pesan = $pesan;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'pesan' => $this->pesan,
            'link' => url('/pesan') // Sesuaikan dengan route aplikasi kamu
        ];
    }
}