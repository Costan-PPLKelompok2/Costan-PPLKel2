<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'kost_id',
        'sender_id',
        'receiver_id',
        'message',
    ];

    public function kost()
    {
        return $this->belongsTo(Kost::class);
    }

    public function sender()
    {
        return $this->belongsTo(User_profile::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User_profile::class, 'receiver_id');
    }


}
