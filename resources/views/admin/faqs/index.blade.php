@extends('layouts.navbar')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Kelola FAQ</h2>
    <a href="{{ route('faq.manage.create') }}" class="btn btn-detail mb-3">+ Tambah FAQ</a>

    @foreach($faqs as $faq)
        <div class="card mb-2">
            <div class="card-body">
                <h5>{{ $faq->question }}</h5>
                <p>{!! nl2br(e($faq->answer)) !!}</p>
                <a href="{{ route('faq.manage.edit', $faq->id) }}" class="btn btn-sm btn-cari">Edit</a>
                <form action="{{ route('faq.manage.destroy', $faq->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    @endforeach
</div>
@endsection
