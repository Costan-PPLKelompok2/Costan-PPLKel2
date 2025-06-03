@extends('layouts.navbar')

@section('content')
<div class="container mt-5">
    <h3>Tambah FAQ Baru</h3>
    <form action="{{ route('faq.manage.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="question">Pertanyaan</label>
            <input type="text" name="question" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="answer">Jawaban</label>
            <textarea name="answer" rows="5" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-detail">Simpan</button>
    </form>
</div>
@endsection
