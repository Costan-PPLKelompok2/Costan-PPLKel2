<div id="slides-shop" class="cover-slides">
        <ul class="slides-container">
            <li class="text-center">
                <img src="images/banner-01.png" alt="">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="m-b-20"><strong>Welcome To <br> Cost'an</strong></h1>
                            <p class="m-b-40">Cari kos idaman anda sekarang!</p>
                            <!-- Button for search, conditionally link to login/register or search -->
                            <p>
                                @guest
                                    <a class="btn hvr-hover" href="{{ route('login') }}">Login untuk Cari Kos Anda</a>
                                @else
                                    <a class="btn hvr-hover" href="{{ route('home.daftarkos') }}">Cari Kos Anda</a> <!-- Gantilah 'search' dengan route pencarian kos Anda -->
                                @endguest
                            </p>
                        </div>
                    </div>
                </div>
            </li>
            <li class="text-center">
                <img src="images/banner-02.jpg" alt="">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="m-b-20"><strong>Welcome To <br> Cost'an</strong></h1>
                            <p class="m-b-40">Cari kos idaman anda sekarang!</p>
                            <p>
                                @guest
                                    <a class="btn hvr-hover" href="{{ route('login') }}">Login untuk Cari Kos Anda</a>
                                @else
                                    <a class="btn hvr-hover" href="{{ route('home.daftarkos') }}">Cari Kos Anda</a>
                                @endguest
                            </p>
                        </div>
                    </div>
                </div>
            </li>
            <li class="text-center">
                <img src="images/banner-03.jpg" alt="">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="m-b-20"><strong>Welcome To <br> Cost'an</strong></h1>
                            <p class="m-b-40">Cari kos idaman anda sekarang!</p>
                            <p>
                                @guest
                                    <a class="btn hvr-hover" href="{{ route('login') }}">Login untuk Cari Kos Anda</a>
                                @else
                                    <a class="btn hvr-hover" href="{{ route('home.daftarkos') }}">Cari Kos Anda</a>
                                @endguest
                            </p>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
        <div class="slides-navigation">
            <a href="#" class="next"><i class="fa fa-angle-right" aria-hidden="true"></i></a>
            <a href="#" class="prev"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
        </div>
    </div>