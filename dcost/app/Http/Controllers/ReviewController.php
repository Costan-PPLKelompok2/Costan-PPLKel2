<?php

namespace App\Http\Controllers; // Pastikan namespace ini ada

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller; // Impor Controller yang hilang

class ReviewController extends Controller
{
    // CREATE
    public function store(Request $request)
    {
        $request->validate([
            'kos_id' => 'required|exists:kos,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'kos_id' => $request->kos_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Review berhasil ditambahkan!');
    }

    // READ
    public function show($kos_id)
    {
        $reviews = Review::where('kos_id', $kos_id)->with('user')->latest()->get();
        return view('reviews.index', compact('reviews'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }

        $review->update($request->only('rating', 'comment'));

        return redirect()->back()->with('success', 'Review berhasil diubah!');
    }

    // DELETE
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }

        $review->delete();

        return redirect()->back()->with('success', 'Review berhasil dihapus!');
    }
}


