<?php
// File: app/Models/Message.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'chat_room_id',
        'sender_id',
        'message',
        'is_read',
        'message_type'
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    protected $attributes = [
        'is_read' => false,
        'message_type' => 'text'
    ];

    /**
     * Relasi ke chat room
     */
    public function chatRoom()
    {
        return $this->belongsTo(ChatRoom::class, 'chat_room_id');
    }

    /**
     * Relasi ke user sebagai pengirim
     */
    public function sender()
    {
        return $this->belongsTo(User_profile::class, 'sender_id');
    }

    /**
     * Format pesan untuk ditampilkan
     */
    public function getFormattedMessageAttribute()
    {
        return nl2br(e($this->message));
    }

    /**
     * Check if message is sent by specific user
     */
    public function isSentBy($userId)
    {
        return $this->sender_id == $userId;
    }

    /**
     * Get time ago format
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Scope untuk filter pesan yang belum dibaca
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope untuk filter pesan dari pengirim tertentu
     */
    public function scopeFromSender($query, $senderId)
    {
        return $query->where('sender_id', $senderId);
    }

    /**
     * Scope untuk filter pesan dalam chat room tertentu
     */
    public function scopeInChatRoom($query, $chatRoomId)
    {
        return $query->where('chat_room_id', $chatRoomId);
    }

    /**
     * Mark pesan sebagai dibaca
     */
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }
}