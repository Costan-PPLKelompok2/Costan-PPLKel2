@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Ulasan untuk Kos Anda</h2>

    @forelse($reviews as $review)
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Kos: {{ $review->kos->name }}</h5>
                <p class="mb-1">Dari: <strong>{{ $review->user->name }}</strong></p>
                <p>⭐ {{ $review->rating }}/5</p>
                <p>{{ $review->comment }}</p>
                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
            </div>
        </div>
    @empty
        <p>Belum ada ulasan untuk kos Anda.</p>
    @endforelse
</div>
@endsection
