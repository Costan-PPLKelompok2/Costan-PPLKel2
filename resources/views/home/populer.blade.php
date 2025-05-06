<div class="categories-shop">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="title-all text-center">
                    <h1>Kos Terpopuler</h1>
                    <p>Berikut merupakan 3 kos terpopuler berdasarkan jumlah dilihat</p>
                </div>
            </div>
        </div>
        <div class="row">
            @forelse($kosPopuler as $kos)
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="shop-cat-box">
                        <img class="img-fluid" style="height: 200px; width: 100%; object-fit: cover;" src="{{ Storage::url($kos->foto) }}" />
                        <a class="btn hvr-hover" href="{{route('kos.show', $kos->id)}}">{{ $kos->nama_kos }} <span class="badge bg-secondary ms-2">{{ $kos->views }} views</span></a>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p>Belum ada kos populer yang dapat ditampilkan.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>