@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Daftar Kos Saya</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($kosList->isEmpty())
        <div class="alert alert-info">Belum ada kos yang ditambahkan.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Nama Kos</th>
                        <th>Alamat</th>
                        <th>Harga</th>
                        <th>Fasilitas</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kosList as $kos)
                    <tr>
                        <td>{{ $kos->nama_kos }}</td>
                        <td>{{ $kos->alamat }}</td>
                        <td>Rp {{ number_format($kos->harga, 0, ',', '.') }}</td>
                        <td>{{ $kos->fasilitas }}</td>
                        <td>
                            @if($kos->foto)
                                <img src="{{ asset('storage/' . $kos->foto) }}" alt="Foto Kos" width="80">
                            @else
                                <em>Tidak ada</em>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('kos.edit', $kos->id) }}" class="btn btn-sm btn-warning me-1">Edit</a>
                            <form action="{{ route('kos.destroy', $kos->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Yakin ingin menghapus kos ini?')" class="btn btn-sm btn-danger">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection