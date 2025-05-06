@extends('layouts.app')

@section('content')
    <div class="container">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <h2>Review untuk Kos: {{ $kos->name }}</h2>

    <!-- Form Tambah Review -->
    @auth
        <form action="{{ route('review.store') }}" method="POST">
            @csrf
            <input type="hidden" name="kos_id" value="{{ $kos->id }}">
            <div class="mb-3">
                <label for="rating">Rating (1-5)</label>
                <input type="number" name="rating" id="rating" class="form-control" min="1" max="5" required>
            </div>
            <div class="mb-3">
                <label for="comment">Ulasan</label>
                <textarea name="comment" id="comment" class="form-control" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Kirim Review</button>
        </form>
    @else
        <p>Silakan <a href="{{ route('login') }}">login</a> untuk memberikan review.</p>
    @endauth

    <hr>

    <!-- Daftar Review -->
    <h3>Ulasan Pengguna</h3>
    @forelse($reviews as $review)
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">{{ $review->user->name }} - ⭐ {{ $review->rating }}/5</h5>
                <p class="card-text">{{ $review->comment }}</p>
                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>

                @auth
                    @if($review->user_id == Auth::id())
                        <a href="{{ route('review.edit', $review->id) }}" class="btn btn-sm btn-warning">Edit</a>

                        <form action="{{ route('review.destroy', $review->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus review ini?')">Hapus</button>
                        </form>
                    @endif
                @endauth
            </div>
        </div>
    @empty
        <p>Belum ada review untuk kos ini.</p>
    @endforelse
</div>
@endsection
