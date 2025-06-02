<?php

namespace App\Notifications;

use App\Models\Message;
use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Mail\NewChatMessageEmail; // Import Mailable kustom Anda

class NewChatMessageNotification extends Notification // implements ShouldQueue (opsional)
{
    use Queueable;

    public Message $message;
    public ChatRoom $chatRoom;
    public User $sender; // Pengirim pesan (penyewa)

    /**
     * Create a new notification instance.
     */
    public function __construct(Message $message, ChatRoom $chatRoom, User $sender)
    {
        $this->message = $message;
        $this->chatRoom = $chatRoom;
        $this->sender = $sender;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // $notifiable di sini adalah instance User (pemilik kos)
        return ['database', 'mail']; // Channel 'mail' akan memanggil method toMail()
    }

    /**
     * Get the mail representation of the notification using a Mailable class.
     *
     * @param  object  $notifiable
     * @return \App\Mail\NewChatMessageEmail // Return type diubah ke Mailable kustom Anda
     */
    public function toMail(object $notifiable): \App\Mail\NewChatMessageEmail
    {
        // $notifiable adalah objek User (pemilik kos) yang akan menerima email
        // Data yang diperlukan oleh Mailable Anda dilewatkan melalui constructornya.
        return (new NewChatMessageEmail($this->message, $this->chatRoom, $this->sender, $notifiable))
                    ->to($notifiable->email); // Mengatur penerima email secara eksplisit.
                                          // Anda juga bisa mengatur ini di dalam method build() Mailable Anda.
    }

    /**
     * Get the array representation of the notification.
     * (Untuk channel 'database' atau 'broadcast')
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'chat_room_id' => $this->chatRoom->id,
            'kos_id' => $this->chatRoom->kos_id,
            'kos_name' => $this->chatRoom->kos->nama_kos,
            'sender_id' => $this->sender->id,
            'sender_name' => $this->sender->name,
            'message_preview' => \Illuminate\Support\Str::limit($this->message->body, 50),
            'link' => route('chat.show', $this->chatRoom->id) . '?notify_id=' . $this->id,
        ];
    }
}
