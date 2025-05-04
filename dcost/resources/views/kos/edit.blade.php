@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Edit Kos</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('kos.update', $kos->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nama_kos" class="form-label">Nama Kos</label>
            <input type="text" class="form-control" id="nama_kos" name="nama_kos" value="{{ $kos->nama_kos }}" required>
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required>{{ $kos->deskripsi }}</textarea>
        </div>

        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <input type="text" class="form-control" id="alamat" name="alamat" value="{{ $kos->alamat }}" required>
        </div>

        <div class="mb-3">
            <label for="harga" class="form-label">Harga (Rp)</label>
            <input type="number" class="form-control" id="harga" name="harga" value="{{ $kos->harga }}" required>
        </div>

        <div class="mb-3">
            <label for="fasilitas" class="form-label">Fasilitas</label>
            <textarea class="form-control" id="fasilitas" name="fasilitas" rows="2" required>{{ $kos->fasilitas }}</textarea>
        </div>

        <div class="mb-3">
            <label for="foto" class="form-label">Foto Kos (Opsional)</label>
            <input class="form-control" type="file" id="foto" name="foto">
            @if($kos->foto)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $kos->foto) }}" alt="Foto Kos" width="120">
                </div>
            @endif
        </div>

        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        <a href="{{ route('kos.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
