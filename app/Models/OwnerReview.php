<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OwnerReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'reviewer_id', // user_id penyewa
        'owner_id', // user_id pemilik kos
        'rating',
        'comment',
    ];

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
