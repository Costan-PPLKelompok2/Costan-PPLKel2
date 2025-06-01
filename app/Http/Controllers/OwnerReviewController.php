<?php

namespace App\Http\Controllers;

use App\Models\OwnerReview;
use App\Models\Kos;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnerReviewController extends Controller
{
    public function create($ownerId)
    {
        $owner = User::findOrFail($ownerId); // ambil data lengkap owner
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
    $owner = User::findOrFail($ownerId); // Pastikan modelnya sesuai, bisa juga Owner::findOrFail()
    $reviews = OwnerReview::where('owner_id', $ownerId)->with('reviewer')->latest()->paginate(5);
    
    return view('owner_reviews.index', compact('owner', 'reviews'));
}
}
