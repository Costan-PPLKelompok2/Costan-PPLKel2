<div class="products-box">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title-all text-center">
                        <h1>Daftar Kos</h1>
                        <p>Berikut merupakan daftar kos yang tersedia</p>
                    </div>
                </div>
            </div>
            <div class="row special-list">
                @forelse($kos as $kos)
                    <div class="col-lg-3 col-md-6 special-grid best-seller">
                        <div class="products-single fix">
                            <div class="box-img-hover">
                                <img src="{{ Storage::url($kos->foto) }}" class="img-fluid" style="height: 200px; width: 100%; object-fit: cover;" alt="{{ $kos->nama_kos }}">
                                <div class="mask-icon">
                                    <a class="cart" href="{{route('kos.show', $kos->id)}}">Lihat Detail</a>
                                </div>
                            </div>
                            <div class="why-text">
                                <h4>{{ $kos->nama_kos }}</h4>
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