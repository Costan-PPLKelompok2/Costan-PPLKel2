<form action="{{ route('review.store') }}" method="POST">
    @csrf
    <input type="hidden" name="kos_id" value="1"> {{-- id kos dummy --}}
    <label>Rating:</label>
    <input type="number" name="rating" min="1" max="5" required>
    <br>
    <label>Komentar:</label>
    <textarea name="comment" required></textarea>
    <br>
    <button type="submit">Submit Review</button>
</form>
