<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PemilikController extends Controller
{
    public function statistik()
{
    $kosList = Kos::where('user_id', auth()->id())
        ->withCount(['views', 'favorites'])
        ->with(['views' => fn($q) => $q->selectRaw('kos_id, DATE(created_at) as date, count(*) as count')->groupBy('date')])
        ->get();

    return view('admin.index', compact('kosList'));
}
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

}
