<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kos;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function store(Kos $kos)
    {
        if (!Auth::user()->favoriteKos->contains($kos->id)) {
            Auth::user()->favoriteKos()->attach($kos->id);
            return back()->with('success', 'Kos berhasil ditambahkan ke favorit.');
        }

        return back()->with('info', 'Kos sudah ada di favorit.');
    }

    public function destroy(Kos $kos)
    {
        Auth::user()->favoriteKos()->detach($kos->id);
        return back()->with('success', 'Kos berhasil dihapus dari favorit.');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');
        $sort = $request->input('sort', 'terbaru');

        $kosFavoritQuery = $user->favoriteKos();

        if ($search) {
            $kosFavoritQuery->where(function ($query) use ($search) {
                $query->where('nama_kos', 'like', '%' . $search . '%')
                    ->orWhere('alamat', 'like', '%' . $search . '%');
            });
        }

        if ($sort === 'terlama') {
            $kosFavoritQuery->orderBy('favorites.created_at', 'asc');
        } else {
            $kosFavoritQuery->orderBy('favorites.created_at', 'desc');
        }

        $kosFavorit = $kosFavoritQuery->get();

        return view('favorit.index', compact('kosFavorit'));
    }
}
