<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Kos Favorit Saya') }}
        </h2>
    </x-slot>

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif

        @if($kosFavorit->isEmpty())
            <div class="alert alert-warning text-center">Belum ada kos yang difavoritkan.</div>
        @else
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($kosFavorit as $kos)
                    <div class="col">
                        <div class="card shadow-sm h-100">
                            @if($kos->foto)
                                <img src="{{ asset('storage/' . $kos->foto) }}" class="card-img-top" alt="Foto Kos">
                            @else
                                <img src="https://via.placeholder.com/300x200?text=Tidak+ada+foto" class="card-img-top" alt="Tidak ada foto">
                            @endif

                            <div class="card-body">
                                <h5 class="card-title">{{ $kos->nama_kos }}</h5>
                                <p class="card-text">{{ \Illuminate\Support\Str::limit($kos->deskripsi, 100) }}</p>
                                <p class="mb-1"><strong>Harga:</strong> Rp {{ number_format($kos->harga, 0, ',', '.') }}</p>
                                <p><strong>Alamat:</strong> {{ $kos->alamat }}</p>
                                <form action="{{ route('kos.unfavorite', $kos->id) }}" method="POST" onsubmit="return confirm('Hapus dari favorit?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm mt-2 w-100">
                                        <i class="bi bi-x-circle"></i> Hapus dari Favorit
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
