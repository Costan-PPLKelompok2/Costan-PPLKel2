@extends('layouts.navbar')

@section('content')
<div class="container mt-5 mb-5">
    <h2 class="text-center mb-4">Frequently Asked Questions (FAQ)</h2>

    <div id="accordion">
        @foreach ($faqs as $faq)
        <div class="card mb-2">
            <div class="card-header" id="heading-{{ $faq->id }}">
                <h5 class="mb-0">
                    <button class="btn btn-link text-left w-100" data-toggle="collapse" data-target="#collapse-{{ $faq->id }}" aria-expanded="true" aria-controls="collapse-{{ $faq->id }}">
                        {{ $faq->question }}
                    </button>
                </h5>
            </div>

            <div id="collapse-{{ $faq->id }}" class="collapse" aria-labelledby="heading-{{ $faq->id }}" data-parent="#accordion">
                <div class="card-body">
                    {!! nl2br(e($faq->answer)) !!}
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <hr class="my-5">

    <h4>Masih punya pertanyaan?</h4>
    <p>Silakan kirim pertanyaan Anda melalui formulir di bawah ini.</p>
    <form method="POST" action="{{ route('faq.help') }}">
        @csrf
        <div class="form-group">
            <label for="name">Nama</label>
            <input type="text" class="form-control" id="name" name="name" required value="{{ old('name', Auth::user()->name ?? '') }}">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required value="{{ old('email', Auth::user()->email ?? '') }}">
        </div>
        <div class="form-group">
            <label for="message">Pertanyaan</label>
            <textarea class="form-control" id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
        </div>
        <button type="submit" class="btn btn-detail">Kirim Pertanyaan</button>
    </form>
</div>
@endsection
