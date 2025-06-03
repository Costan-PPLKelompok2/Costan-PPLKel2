<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class KosUpdatedNotification extends Notification
{
    use Queueable;
    protected $kos;
    protected $changes;

    /**
     * Create a new notification instance.
     */
    public function __construct($kos, $changes)
    {
        $this->kos = $kos;
        $this->changes = $changes;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'kos_id' => $this->kos->id,
            'nama_kos' => $this->kos->nama_kos,
            'message' => 'Kos "' . $this->kos->nama_kos . '" mengalami perubahan: ' . implode(', ', $this->changes),
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
