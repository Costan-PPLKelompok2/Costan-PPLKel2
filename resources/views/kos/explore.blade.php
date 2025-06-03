<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold text-center" style="color: #4c51bf;">
            {{ __('Jelajahi Kos') }}
        </h2>
    </x-slot>

    <div class="container mt-5">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @forelse($kosList as $kos)
                <div class="col">
                    <div class="card shadow-sm h-100 border-0">
                        @if($kos->foto)
                            <img src="{{ asset('storage/' . $kos->foto) }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Foto Kos">
                        @else
                            <img src="https://via.placeholder.com/400x200?text=Tidak+Ada+Foto" class="card-img-top" alt="Kos Tanpa Foto">
                        @endif

                        <div class="card-body">
                            <h5 class="card-title">{{ $kos->nama_kos }}</h5>
                            <p class="card-text mb-1"><strong>Alamat:</strong> {{ $kos->alamat }}</p>
                            <p class="card-text mb-1"><strong>Harga:</strong> Rp {{ number_format($kos->harga, 0, ',', '.') }}</p>
                            <p class="card-text"><strong>Fasilitas:</strong> {{ Str::limit($kos->fasilitas, 80) }}</p>

                            {{-- Tombol favorit --}}
                            @if (!auth()->user()->favoriteKos->contains($kos->id))
                                <form action="{{ route('kos.favorite', $kos->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-outline-danger btn-sm w-100 mt-2">
                                        <i class="bi bi-heart"></i> Simpan ke Favorit
                                    </button>
                                </form>
                            @else
                                <span class="badge bg-danger mt-2 d-inline-block w-100 text-center">
                                    <i class="bi bi-heart-fill me-1"></i> Sudah Difavoritkan
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info">
                    Tidak ada kos tersedia.
                </div>
            @endforelse
        </div>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</x-app-layout>
