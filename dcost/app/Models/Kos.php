<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kos extends Model
{
    protected $fillable = ['name', 'alamat', 'harga', 'fasilitas'];
    
public function reviews()
{
    return $this->hasMany(Review::class);
}

}
