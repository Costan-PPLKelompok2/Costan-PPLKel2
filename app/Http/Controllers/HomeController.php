<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Ini method index kamu yang menangani $kosList
    public function index(Request $request)
    {
        // Ambil filter dari query string kalau perlu,
        // atau langsung paginate semuanya:
        $kosList = Kos::paginate(12)->withQueryString();

        return view('home.dashboard', compact('kosList'));
    }

    // Jika masih ada method redirect() atau lainnya, biarkan di sini
    public function redirect()
    {
        // ... kode redirect lama
    }
}
