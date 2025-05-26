<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MinatKosBaru extends Notification
{
    private $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'nama_user' => $this->user->name,
            'pesan' => 'Ada yang tertarik dengan kos Anda!',
            'link' => url('/pemilik/kos')
        ];
    }
}
