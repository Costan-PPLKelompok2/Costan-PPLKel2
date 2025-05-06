@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Review</h2>

    <form action="{{ route('review.update', $review->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="rating">Rating</label>
            <input type="number" name="rating" id="rating" class="form-control" value="{{ $review->rating }}" min="1" max="5" required>
        </div>

        <div class="mb-3">
            <label for="comment">Ulasan</label>
            <textarea name="comment" id="comment" class="form-control" rows="3" required>{{ $review->comment }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
