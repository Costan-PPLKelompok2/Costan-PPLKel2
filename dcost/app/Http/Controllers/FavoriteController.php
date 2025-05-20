<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function store(Kos $kos)
    {
        if (!Auth::user()->favoriteKos->contains($kos->id)) {
            Auth::user()->favoriteKos()->attach($kos->id);
            return back()->with('success', 'Kos ditambahkan ke favorit.');
        }

        return back()->with('info', 'Kos sudah ada di favorit.');
    }

    public function destroy(Kos $kos)
    {
        Auth::user()->favoriteKos()->detach($kos->id);
        return back()->with('success', 'Kos dihapus dari favorit.');
    }

    public function index()
    {
        $kosFavorit = Auth::user()->favoriteKos()->latest()->get();
        return view('favorit.index', compact('kosFavorit'));
    }
}
