<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kost extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'name',
        'description',
        'price',
        'address',
    ];

    public function owner()
    {
        return $this->belongsTo(User_profile::class, 'owner_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
