@extends('layouts.app')

@section('content')
<div class="p-6 max-w-xl mx-auto">
    <h2 class="text-xl font-bold mb-4">Review untuk {{ $kos->name }}</h2>

    @if (session('success'))
        <div class="mb-4 text-green-600">{{ session('success') }}</div>
    @endif

    <form action="{{ route('review.submit', $kos->id) }}" method="POST" class="mb-6">
        @csrf
        <label class="block mb-2">Rating (1-5):</label>
        <input type="number" name="rating" min="1" max="5" class="border p-2 w-full mb-3" required>

        <label class="block mb-2">Ulasan:</label>
        <textarea name="comment" class="border p-2 w-full mb-3" required></textarea>


        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Kirim Review</button>
    </form>

    <h3 class="text-lg font-semibold mb-2">Review Sebelumnya</h3>
    @forelse ($reviews as $review)
        <div class="border p-3 mb-3 rounded">
            <div class="font-medium">Rating: {{ $review->rating }}</div>
            <p>{{ $review->comment }}</p>
        </div>
    @empty
        <p>Belum ada review.</p>
    @endforelse
</div>
@endsection
