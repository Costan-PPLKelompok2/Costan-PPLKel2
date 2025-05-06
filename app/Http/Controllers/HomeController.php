<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Kos;
class HomeController extends Controller
{
    public function index(){
        $kos = Kos::all();
        $kosPopuler = Kos::orderBy('views', 'desc')->take(3)->get();
        return view('home.dashboard', compact('kosPopuler', 'kos'));        
    }
}