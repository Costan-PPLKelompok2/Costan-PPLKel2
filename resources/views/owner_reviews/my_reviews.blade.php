<h2>Review untuk Anda</h2>

@foreach ($reviews as $review)
    <div>
        <strong>{{ $review->reviewer->name }}</strong> - Rating: {{ $review->rating }}/5
        <p>{{ $review->comment }}</p>
    </div>
@endforeach
