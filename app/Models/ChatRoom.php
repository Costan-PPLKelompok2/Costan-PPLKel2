<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ChatRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'kos_id',
        'tenant_id',
        'owner_id',
    ];

    public function kos()
    {
        return $this->belongsTo(Kos::class);
    }

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    // Untuk mendapatkan partisipan lain dalam chat room
    public function otherParticipant()
    {
        if (Auth::id() == $this->tenant_id) {
            return $this->owner;
        } elseif (Auth::id() == $this->owner_id) {
            return $this->tenant;
        }
        return null;
    }

    // Untuk menghitung pesan yang belum dibaca oleh user saat ini
    public function unreadMessagesCount()
    {
        return $this->messages()
                    ->where('sender_id', '!=', Auth::id())
                    ->whereNull('read_at')
                    ->count();
    }

     // Accessor untuk memperbarui updated_at chat_room saat ada pesan baru
     public function touchLastActivity()
     {
         $this->touch(); // Ini akan memperbarui kolom updated_at di chat_rooms
     }
}
