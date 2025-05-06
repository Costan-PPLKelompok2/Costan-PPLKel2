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
        // 
    }
}