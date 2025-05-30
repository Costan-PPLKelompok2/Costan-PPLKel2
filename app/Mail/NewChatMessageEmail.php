<?php

namespace App\Mail;
use App\Models\ChatRoom; // Import model yang diperlukan
use App\Models\Message;  // Import model yang diperlukan
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewChatMessageEmail extends Mailable
{
    use Queueable, SerializesModels;

    public Message $message;
    public ChatRoom $chatRoom;
    public User $sender; // Pengirim pesan
    public User $recipient;

    /**
     * Create a new message instance.
     */
    public function __construct(Message $message, ChatRoom $chatRoom, User $sender, User $recipient)
    {
        $this->message = $message;
        $this->chatRoom = $chatRoom;
        $this->sender = $sender;
        $this->recipient = $recipient;
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $kosName = $this->chatRoom->kos ? $this->chatRoom->kos->nama_kos : 'Kos Anda';
        return new Envelope(
            subject: 'Pesan Baru di ' . $kosName . ' dari ' . $this->sender->name,
        );
    }
    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Buat view Blade sederhana untuk email, misalnya: resources/views/emails/new_chat_message.blade.php
        return new Content(
            view: 'emails.new_chat_message',
            with: [
                'senderName' => $this->sender->name,
                'messageBody' => $this->message->body,
                'kosName' => $this->chatRoom->kos ? $this->chatRoom->kos->nama_kos : '',
                'chatLink' => route('chat.show', $this->chatRoom->id),
                'recipientName' => $this->recipient->name,
            ],
        );
    }
}