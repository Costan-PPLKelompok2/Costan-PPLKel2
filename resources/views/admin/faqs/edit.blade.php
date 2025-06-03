@extends('layouts.navbar')

@section('content')
<div class="container mt-5 mb-5">
    <h3 class="mb-4">Edit FAQ</h3>

    <form action="{{ route('faq.manage.update', $faq->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="question">Pertanyaan</label>
            <input type="text" name="question" class="form-control" value="{{ old('question', $faq->question) }}" required>
        </div>

        <div class="form-group">
            <label for="answer">Jawaban</label>
            <textarea name="answer" rows="6" class="form-control" required>{{ old('answer', $faq->answer) }}</textarea>
        </div>

        <button type="submit" class="btn btn-detail">Simpan Perubahan</button>
        <a href="{{ route('faq.manage.index') }}" class="btn btn-secondary ml-2">Batal</a>
    </form>
</div>
@endsection
