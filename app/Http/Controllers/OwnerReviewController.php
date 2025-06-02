<?php

namespace App\Http\Controllers;

use App\Models\OwnerReview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnerReviewController extends Controller
{
    public function create($ownerId)
    {
        $owner = User::findOrFail($ownerId);
        return view('owner_reviews.create', compact('owner'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'owner_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        OwnerReview::create([
            'reviewer_id' => Auth::id(),
            'owner_id' => $request->owner_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('owner-reviews.show', $request->owner_id)
                         ->with('success', 'Ulasan berhasil ditambahkan!');
    }

    public function show($ownerId)
    {
        $owner = User::findOrFail($ownerId);
        $reviews = OwnerReview::where('owner_id', $ownerId)->with('reviewer')->latest()->paginate(5);
        return view('owner_reviews.index', compact('owner', 'reviews'));
    }

    public function edit($id)
    {
        $review = OwnerReview::findOrFail($id);

        // Optional: pastikan hanya reviewer yang bisa edit review-nya sendiri
        if ($review->reviewer_id !== Auth::id()) {
            abort(403, 'Kamu tidak punya akses untuk mengedit ulasan ini.');
        }

        return view('owner_reviews.edit', compact('review'));
    }

    public function update(Request $request, $id)
    {
        $review = OwnerReview::findOrFail($id);

        if ($review->reviewer_id !== Auth::id()) {
            abort(403, 'Kamu tidak punya akses untuk mengubah ulasan ini.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('owner-reviews.show', $review->owner_id)
                         ->with('success', 'Ulasan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $review = OwnerReview::findOrFail($id);

        if ($review->reviewer_id !== Auth::id()) {
            abort(403, 'Kamu tidak punya akses untuk menghapus ulasan ini.');
        }

        $review->delete();

        return redirect()->route('owner-reviews.show', $review->owner_id)
                         ->with('success', 'Ulasan berhasil dihapus!');
    }
}
