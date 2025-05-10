<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Message;

class NewMessageNotification extends Notification
{
    use Queueable;

    protected $message;

    /**
     * Create a new notification instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail']; // Use database and mail channels
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $kost = $this->message->kost;
        $sender = $this->message->sender;
        
        return (new MailMessage)
                    ->subject('Pesan Baru tentang ' . $kost->name)
                    ->greeting('Halo, ' . $notifiable->name . '!')
                    ->line('Anda mendapatkan pesan baru dari ' . $sender->name . ' tentang kos ' . $kost->name . '.')
                    ->line('Isi pesan: "' . substr($this->message->message, 0, 100) . (strlen($this->message->message) > 100 ? '...' : '') . '"')
                    ->action('Lihat Pesan', url('/chat/show/' . $kost->id . '?user_profile_id=' . $notifiable->id))
                    ->line('Terima kasih telah menggunakan aplikasi kami!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message_id' => $this->message->id,
            'kost_id' => $this->message->kost_id,
            'sender_id' => $this->message->sender_id,
            'sender_name' => $this->message->sender->name,
            'message_preview' => substr($this->message->message, 0, 100),
            'kost_name' => $this->message->kost->name,
        ];
    }
}