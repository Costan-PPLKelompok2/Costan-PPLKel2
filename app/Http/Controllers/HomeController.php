<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $kosPopuler = Kos::orderBy('views', 'desc')->take(3)->get();

        $filter = $request->input('filter', 'all');
        $query = Kos::query();
        
        switch ($filter) {
            case 'latest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'popular':
                $query->orderBy('views', 'desc');
                break;
            case 'price_low':
                $query->orderBy('harga', 'asc');
                break;
            case 'price_high':
                $query->orderBy('harga', 'desc');
                break;
            default:
                break;
            }
        $kost = $query->get();
        return view('home.dashboard', compact('kosPopuler', 'kost'));        
    }

    public function daftarKos(Request $request)
    {
        $kosPopuler = Kos::orderBy('views', 'desc')->take(3)->get();
        $filter = $request->input('filter', 'all');
        $query = Kos::query();
        
        switch ($filter) {
            case 'latest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'popular':
                $query->orderBy('views', 'desc');
                break;
            case 'price_low':
                $query->orderBy('harga', 'asc');
                break;
            case 'price_high':
                $query->orderBy('harga', 'desc');
                break;
            default:
                $query->inRandomOrder();
                break;
            }
        $kost = $query->get();
        return view('home.kos', compact('kost', 'kosPopuler'));
    }


    public function redirect(Request $request)
    {
        if ($request->has('error')) {
            session()->flash('error', $request->error);
        }
        if ($request->has('success')) {
            session()->flash('success', $request->success);
        }
        if ($request->has('info')) {
            session()->flash('info', $request->info);
        }
        if ($request->has('warning')) {
            session()->flash('warning', $request->warning);
        }
        if ($request->has('message')) {
            session()->flash('message', $request->message);
        }
        if ($request->has('status')) {
            session()->flash('status', $request->status);
        }
        
        if (auth()->check()) {  
            if (auth()->user()->role == 'admin') {
                return redirect()->route('admin.index');
            } elseif (auth()->user()->role == 'pemilik') {
                return redirect()->route('kos.index');
            } elseif (auth()->user()->role == 'user') {
                return redirect()->route('home.index');
            } else {
                return redirect()->route('home.index');
            }
        } else {
            return redirect()->route('home.index');
        }
    }
}