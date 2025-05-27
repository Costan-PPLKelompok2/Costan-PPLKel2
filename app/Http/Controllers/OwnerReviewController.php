<?php

namespace App\Http\Controllers;

use App\Models\OwnerReview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnerReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'owner_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        // Cek apakah sudah pernah menyewa kos milik owner ini
        $hasRented = Auth::user()->rentals()->where('owner_id', $request->owner_id)->exists();
        if (!$hasRented) {
            return back()->withErrors(['Anda hanya bisa mereview pemilik kos yang pernah Anda sewa.']);
        }

        OwnerReview::create([
            'reviewer_id' => Auth::id(),
            'owner_id' => $request->owner_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Review berhasil ditambahkan.');
    }

    public function showReviewsForOwner(User $owner)
    {
        $reviews = OwnerReview::where('owner_id', $owner->id)->with('reviewer')->latest()->get();
        return view('owner_reviews.index', compact('owner', 'reviews'));
    }

    public function myReviews()
    {
        $reviews = OwnerReview::where('owner_id', Auth::id())->with('reviewer')->latest()->get();
        return view('owner_reviews.my_reviews', compact('reviews'));
    }
}
