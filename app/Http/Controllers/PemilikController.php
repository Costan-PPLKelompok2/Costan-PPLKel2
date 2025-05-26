<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PemilikController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = Kos::query();

        if ($user->role != 'admin') {
            $query->where('user_id', $user->id);
        }

        $viewsStatistik = $query->select('nama_kos', 'views')
                        ->orderByDesc('views')
                        ->get();
        $totalKos = $query->count();
        $totalViews = $query->sum('views');

        return view('admin.index', compact('viewsStatistik','totalKos','totalViews', 'user'));
    }
    public function statistik()
    {
        $user = Auth::user();

        if ($user->role != 'pemilik') {
            return redirect()->route('home.index')->with('error', 'Akses ditolak.');
        }

        $kosList = Kos::where('user_id', $user->id)->get();
        $totalKos = $kosList->count();
        $totalViews = $kosList->sum('views');

        return view('admin.index', compact('kosList', 'totalKos', 'totalViews'));
    }
    

}
