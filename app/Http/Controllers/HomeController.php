<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {

        $kosList = Kos::paginate(12)->withQueryString();
        $kos = Kos::all();
        $kosPopuler = Kos::orderBy('views', 'desc')->take(3)->get();
      
        return view('home.dashboard', compact('kosPopuler', 'kos','kosList'));        
    }


    public function redirect()
    {
        if (auth()->user()->role == 'admin') {
            return redirect()->route('admin.index');
        } elseif (auth()->user()->role == 'pemilik') {
            return redirect()->route('kos.index');
        } elseif (auth()->user()->role == 'user') {
            return redirect()->route('dashboard.index');
        } else {
            return redirect()->route('dashboard.index');
        }
    }
}