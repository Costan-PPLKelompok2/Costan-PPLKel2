<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class User_profile extends Model
{
    use HasFactory, Notifiable;

    // Nama tabel yang digunakan
    protected $table = 'users_profile';

    // Kolom yang bisa diisi (mass assignment)
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'price_range',
        'location',
        'room_type',
        'facilities',
        'photo',
    ];

    /**
     * Route notifications for the mail channel.
     */
    public function routeNotificationForMail()
    {
        return $this->email;
    }

    /**
     * Get the kosts owned by this user
     */
    public function ownedKosts()
    {
        return $this->hasMany(Kost::class, 'owner_id');
    }

    /**
     * Get messages sent by this user
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get messages received by this user
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }
}