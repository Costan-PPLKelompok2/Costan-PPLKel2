<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PemilikController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!in_array($user->role, ['pemilik', 'admin'])) {
            return redirect()->route('home.index')->with('error', 'Akses ditolak.');
        }

        $query = Kos::query();

        $totalAllKos = Kos::count();
        $totalAllViews = Kos::sum('views');

        if ($user->role === 'pemilik') {
            $query->where('user_id', $user->id);
        }

        $viewsStatistik = ($user->role === 'admin')
            ? Kos::select('nama_kos', 'views')->orderByDesc('views')->get()
            : Kos::where('user_id', $user->id)->select('nama_kos', 'views')->orderByDesc('views')->get();

        $totalKos = $query->count();
        $totalViews = $query->sum('views');

        $pemilikStats = [];

        if ($user->role === 'admin') {
            $pemilikStats = User::where('role', 'pemilik')
                ->with('kos')
                ->get()
                ->map(function ($pemilik) {
                    return [
                        'nama' => $pemilik->name,
                        'jumlahKos' => $pemilik->kos->count(),
                        'jumlahViews' => $pemilik->kos->sum('views'),
                        'kosViews' => $pemilik->kos
                        ->sortByDesc('views')
                        ->map(function($kos) {
                            return [
                                'nama_kos' => $kos->nama_kos,
                                'views' => $kos->views,
                            ];
                        }),
                    ];
                });
        }

        return view('admin.index', [
            'user' => $user,
            'totalAllKos' => $totalAllKos,
            'totalAllViews' => $totalAllViews,
            'viewsStatistik' => $viewsStatistik,
            'totalKos' => $totalKos,
            'totalViews' => $totalViews,
            'pemilikStats' => $pemilikStats,
        ]);
    }
}
