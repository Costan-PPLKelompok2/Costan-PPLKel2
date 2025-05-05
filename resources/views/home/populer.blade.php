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
            @foreach($kosPopuler as $kos)
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="shop-cat-box">
                        <img class="img-fluid" style="height: 200px; width: 100%; object-fit: cover;" src="{{ Storage::url($kos->foto) }}" />
                        <a class="btn hvr-hover" href="#">{{ $kos->nama_kos }}</a>
                    </div>
                </div>
            @endforeach
                </div>
            </div>
        </div>
    </div>