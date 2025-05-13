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

        if ($user->role == 'admin') {
            $data = Kos::select('user_id')
                ->selectRaw('COUNT(*) as total_kos')
                ->groupBy('user_id')
                ->with('pemilik')
                ->get();
        } else {
            $data = Kos::where('user_id', $user->id)
                ->select('nama_kos')
                ->selectRaw('views as total_kos')
                ->get();
        }

        return view('admin.index', compact('data', 'user'));
    }

}
