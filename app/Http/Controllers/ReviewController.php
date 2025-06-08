<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Kos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // 🔹 CREATE FORM
    public function create($id)
    {
        $kos = Kos::findOrFail($id); // Ambil data kos dari database
        return view('review.create', compact('kos')); // kirim ke view
    }

    // 🔹 STORE REVIEW
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
            //'reviewer_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Review berhasil ditambahkan!');
    }

    // 🔹 READ ALL REVIEWS for a KOS (Penyewa yang mau lihat ulasan sebelumnya)
    public function show($kos_id)
    {
        $kos = Kos::findOrFail($kos_id);
        $reviews = Review::where('kos_id', $kos_id)->with('user')->latest()->get();
        return view('review.index', compact('kos', 'reviews'));
    }

    // 🔹 READ REVIEWS for OWNER
    public function ownerReviews()
    {
        $kosMilikPemilik = Kos::where('user_id', Auth::id())->pluck('id');
    
        $reviews = Review::with(['user', 'kos'])
                    ->whereIn('kos_id', $kosMilikPemilik)
                    ->latest()
                    ->get();
    
        return view('review.owner', compact('reviews'));
    
    
    }

    // 🔹 UPDATE REVIEW
    public function update(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $review = Review::findOrFail($id);

        if ($review->user_id !== Auth::id()) {
            abort(403);
        }

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('review.show', $review->kos_id)->with('success', 'Review berhasil diubah!');

    }

    //EDIT
        public function edit($id)
    {
        $review = Review::findOrFail($id);

        if ($review->user_id !== Auth::id()) {
            abort(403);
        }

        return view('review.edit', compact('review'));
    }


    // 🔹 DELETE REVIEW
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
