<?php

// app/Listeners/NotifyMessageReceiver.php
namespace App\Listeners;

use App\Events\MessageCreated;
use App\Notifications\NewMessageNotification;

class NotifyMessageReceiver
{
    public function handle(MessageCreated $event)
    {
        $message = $event->message;
        $chatRoom = $message->chatRoom;

        $receiverId = ($message->sender_id === $chatRoom->tenant_id)
            ? $chatRoom->owner_id
            : $chatRoom->tenant_id;

        $receiver = \App\Models\User::find($receiverId);
        $receiver->notify(new NewMessageNotification($message));
    }
}

