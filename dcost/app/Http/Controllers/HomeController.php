<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Kos;
class HomeController extends Controller
{
    public function index(){
        $kosPopuler = Kos::withCount('views')
        ->orderByDesc('views_count')
        ->take(10)
        ->get();
        
        return view("home.dashboard", compact('kosPopuler'));
    }
}
