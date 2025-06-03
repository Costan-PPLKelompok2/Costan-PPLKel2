<?php

namespace App\Http\Controllers;
use App\Events\MessageCreated;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'chat_room_id' => 'required|exists:chat_rooms,id',
            'body' => 'required|string',
        ]);

        $message = Message::create([
            'chat_room_id' => $validated['chat_room_id'],
            'sender_id' => auth()->id(),
            'body' => $validated['body'],
        ]);

        event(new MessageCreated($message));

        return response()->json(['message' => 'Pesan terkirim']);
    }
}
