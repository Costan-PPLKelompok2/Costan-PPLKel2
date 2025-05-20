<?php

use App\Models\Kos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
     public function store(Kos $kos)
    {
        // Cek apakah user sudah memfavoritkan kos ini
        if (!Auth::user()->favoriteKos->contains($kos->id)) {
            Auth::user()->favoriteKos()->attach($kos->id);
            return back()->with('success', 'Kos ditambahkan ke favorit.');
        }

        return back()->with('info', 'Kos sudah ada di favorit.');
    }
}
