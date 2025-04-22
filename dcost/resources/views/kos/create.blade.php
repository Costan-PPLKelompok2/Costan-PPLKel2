@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Tambah Kos</h2>
    <form action="{{ route('kos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="text" name="nama_kos" placeholder="Nama Kos" required><br>
        <textarea name="deskripsi" placeholder="Deskripsi"></textarea><br>
        <input type="text" name="alamat" placeholder="Alamat"><br>
        <input type="number" name="harga" placeholder="Harga"><br>
        <textarea name="fasilitas" placeholder="Fasilitas"></textarea><br>
        <input type="file" name="foto"><br>
        <button type="submit">Simpan</button>
    </form>
</div>
@endsection
