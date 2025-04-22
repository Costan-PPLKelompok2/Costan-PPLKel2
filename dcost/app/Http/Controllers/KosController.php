<?php

namespace App\Http\Controllers;
use App\Models\Kos;
use App\Models\KosView;
use Illuminate\Http\Request;

class KosController extends Controller
{
    public function populer()
    {
    $kosPopuler = Kos::with('pemilik')
        ->withCount('views')
        ->orderBy('views_count', 'desc')
        ->take(10)
        ->get();

    return view('dashboard.populer', compact('kosPopuler'));
    }
    public function show($id)
    {
    $kos = Kos::findOrFail($id);

    KosView::create([
        'kos_id' => $kos->id,
        'ip' => request()->ip(),
    ]);

    return view('dashboard.show', compact('kos'));
    }

}
