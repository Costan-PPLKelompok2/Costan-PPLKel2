<?php

namespace App\Http\Controllers;
use\App\Models\Kos;
use Illuminate\Http\Request;

class PemilikController extends Controller
{
    public function statistik()
{
    $kosList = Kos::where('user_id', auth()->id())
        ->withCount(['views', 'favorites'])
        ->with(['views' => fn($q) => $q->selectRaw('kos_id, DATE(created_at) as date, count(*) as count')->groupBy('date')])
        ->get();

    return view('pemilik.kos.statistik', compact('kosList'));
}

}
