<form method="POST" action="{{ route('owner-reviews.store') }}">
    @csrf
    <input type="hidden" name="owner_id" value="{{ $owner->id }}">

    <label for="rating">Rating:</label>
    <select name="rating" required>
        <option value="">-- Pilih --</option>
        @for ($i = 1; $i <= 5; $i++)
            <option value="{{ $i }}">{{ $i }}</option>
        @endfor
    </select>

    <label for="comment">Komentar:</label>
    <textarea name="comment" rows="4" cols="50"></textarea>

    <button type="submit">Kirim Review</button>
</form>
