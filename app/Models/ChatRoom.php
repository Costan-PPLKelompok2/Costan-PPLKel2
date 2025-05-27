<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatRoom extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kos_id',
        'tenant_id', 
        'owner_id'
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    /**
     * Relasi ke tabel kos
     */
    public function kos()
    {
        return $this->belongsTo(Kos::class, 'kos_id');
    }

    /**
     * Relasi ke user sebagai penyewa
     */
    public function tenant()
    {
        return $this->belongsTo(User_profile::class, 'tenant_id');
    }

    /**
     * Relasi ke user sebagai pemilik kos
     */
    public function owner()
    {
        return $this->belongsTo(User_profile::class, 'owner_id');
    }

    /**
     * Relasi ke messages
     */
    public function messages()
    {
        return $this->hasMany(Message::class, 'chat_room_id');
    }

    /**
     * Get latest message
     */
    public function latestMessage()
    {
        return $this->hasOne(Message::class, 'chat_room_id')->latest();
    }

    /**
     * Get unread messages count for specific user
     */
    public function unreadMessagesCount($userId)
    {
        return $this->messages()
                    ->where('sender_id', '!=', $userId)
                    ->where('is_read', false)
                    ->count();
    }

    /**
     * Get the other participant in chat
     */
    public function getOtherParticipant($currentUserId)
    {
        if ($this->tenant_id == $currentUserId) {
            return $this->owner;
        }
        return $this->tenant;
    }

    /**
     * Check if user is participant of this chat room
     */
    public function isParticipant($userId)
    {
        return $this->tenant_id == $userId || $this->owner_id == $userId;
    }

    /**
     * Scope untuk filter chat rooms berdasarkan user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('tenant_id', $userId)
                     ->orWhere('owner_id', $userId);
    }
}

?>