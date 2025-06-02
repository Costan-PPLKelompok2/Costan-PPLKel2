@extends('layouts.navbar')

@section('content')
<div class="products-box" id="daftarkos">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title-all text-center">
                        <h1>Daftar Kos</h1>
                        <p>Berikut merupakan daftar kos yang tersedia</p>
                    </div>
                </div>
            </div>
            <div class="container mb-4">
            <div class="btn-group" role="group" aria-label="Filter Kos">
                <a href="{{ route('home.daftarkos', ['filter' => 'all']) }}#daftarkos" class="btn btn-outline-primary {{ request('filter') == 'all' ? 'active' : '' }}">Semua</a>
                <a href="{{ route('home.daftarkos', ['filter' => 'latest']) }}#daftarkos" class="btn btn-outline-primary {{ request('filter') == 'latest' ? 'active' : '' }}">Terbaru</a>
                <a href="{{ route('home.daftarkos', ['filter' => 'popular']) }}#daftarkos" class="btn btn-outline-primary {{ request('filter') == 'popular' ? 'active' : '' }}">Populer</a>
                <a href="{{ route('home.daftarkos', ['filter' => 'price_low']) }}#daftarkos" class="btn btn-outline-primary {{ request('filter') == 'price_low' ? 'active' : '' }}">Harga Terendah</a>
                <a href="{{ route('home.daftarkos', ['filter' => 'price_high']) }}#daftarkos" class="btn btn-outline-primary {{ request('filter') == 'price_high' ? 'active' : '' }}">Harga Tertinggi</a>
            </div>
            </div>
            <div class="row special-list">
                @forelse($kost as $kos)
                    <div class="col-lg-3 col-md-6 special-grid best-seller">
                        <div class="products-single fix shadow rounded">
                            <div class="box-img-hover">
                                <img src="{{ Storage::url($kos->foto) }}" class="img-fluid rounded-top" style="height: 200px; width: 100%; object-fit: cover;" alt="{{ $kos->nama_kos }}">
                                <div class="mask-icon">
                                    <a class="cart" href="{{route('kos.show', $kos->id)}}">Lihat Detail</a>
                                </div>
                            </div>
                            <div class="why-text" >
                                <h4 style="display: flex; justify-content: space-between; align-items: center;">{{ $kos->nama_kos }}
                                    @if(request('filter') == 'popular')
                                        <span class="badge bg-secondary text-white" style="white-space: nowrap;">{{ $kos->views }} views</span>
                                    @endif
                                </h4>
                                <h5>Rp. {{ number_format($kos->harga, 0, ',', '.') }}</h5>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p>Belum ada daftar kos.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection