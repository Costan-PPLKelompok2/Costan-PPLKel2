<!DOCTYPE html>
<html lang="en">
<!-- Basic -->
<head>
@include('home.head')
</head>

<body>
    <!-- Start Main Top -->
    <!-- @include('home.navinfo') -->
    <!-- End Main Top -->
    @php
        // Pastikan variabel facilities selalu ada
        $facilities = $facilities ?? [];
    @endphp

    <!-- Start Main Top -->
    <header class="main-header">
        <!-- Start Navigation -->
        @include("home.navbar")
        <!-- End Navigation -->
    </header>
    <!-- End Main Top -->

    <!-- Start Top Search -->
    <div class="top-search">
    <div class="container">
        <form action="{{ route('home.dashboard') }}" method="GET">
        <div class="input-group">
            <!-- Tombol submit -->
            <button class="btn input-group-text" type="submit">
            <i class="fa fa-search"></i>
            </button>

            <!-- Input dengan name -->
            <input 
            type="text" 
            name="search" 
            class="form-control" 
            placeholder="Cari kos..." 
            value="{{ request('search') }}"
            >

            <!-- Tombol clear (opsional, butuh JS) -->
            <span class="input-group-text close-search">
            <i class="fa fa-times"></i>
            </span>
        </div>
        </form>
    </div>
    </div>
    <!-- End Top Search -->

     <!-- Extended Search Bar -->
    <div class="top-search bg-dark py-4">
      <div class="container">
        <form action="{{ route('kos.index') }}" method="GET">
          <div class="row g-2 align-items-center">
            <!-- Submit Button -->
            <div class="col-auto">
              <button class="btn btn-outline-light" type="submit">
                <i class="fa fa-search"></i>
              </button>
            </div>
            <!-- Keyword -->
            <div class="col-md-3">
              <input type="text" name="search" class="form-control" placeholder="Cari kos..." value="{{ request('search') }}">
            </div>
            <!-- Location Autocomplete -->
            <div class="col-md-3">
              <input id="location-input" type="text" name="location" class="form-control" placeholder="Cari lokasi..." value="{{ request('location') }}">
              <input type="hidden" id="loc_lat" name="loc_lat" value="{{ request('loc_lat') }}">
              <input type="hidden" id="loc_lng" name="loc_lng" value="{{ request('loc_lng') }}">
            </div>
            <!-- Price Min/Max -->
            <div class="col-md-2">
              <input type="number" name="price_min" class="form-control" placeholder="Min Harga" value="{{ request('price_min') }}">
            </div>
            <div class="col-md-2">
              <input type="number" name="price_max" class="form-control" placeholder="Max Harga" value="{{ request('price_max') }}">
            </div>
            <!-- Facilities Multi-select -->
            <div class="col-md-3">
              <select name="facilities[]" class="form-select" multiple title="Tahan Ctrl/Cmd untuk pilih banyak">
                @foreach($facilities as $f)
                  <option value="{{ $f->id }}" {{ in_array($f->id, (array) request('facilities', [])) ? 'selected':'' }}>
                    {{ $f->name }}
                  </option>
                @endforeach
              </select>
            </div>
            <!-- Radius -->
            <div class="col-md-2">
              <select name="radius" class="form-select">
                <option value="">Radius (km)</option>
                <option value="1" {{ request('radius')=='1'?'selected':'' }}>1 km</option>
                <option value="3" {{ request('radius')=='3'?'selected':'' }}>3 km</option>
                <option value="5" {{ request('radius')=='5'?'selected':'' }}>5 km</option>
                <option value="10" {{ request('radius')=='10'?'selected':'' }}>10 km</option>
              </select>
            </div>
            <!-- Clear Filters -->
            <div class="col-auto">
              <button type="button" class="btn btn-outline-light" onclick="window.location='{{ route('kos.index') }}'"><i class="fa fa-times"></i></button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Start Slider -->
    <div id="slides-shop" class="cover-slides">
        <ul class="slides-container">
            <li class="text-center">
                <img src="images/banner-01.png" alt="">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="m-b-20"><strong>Welcome To <br> Cost'an</strong></h1>
                            <p class="m-b-40">Cari kos idaman anda sekarang!</p>
                            <p><a class="btn hvr-hover" href="#">Cari Kos Anda</a></p>
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
                            <p><a class="btn hvr-hover" href="#">Cari Kos Anda</a></p>
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
                            <p><a class="btn hvr-hover" href="#">Cari Kos Anda</a></p>
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
    <!-- End Slider -->

    <!-- Start Categories  -->
    <div class="categories-shop">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="shop-cat-box">
                        <img class="img-fluid" src="images/categories_img_01.jpg" alt="" />
                        <a class="btn hvr-hover" href="#">Lorem ipsum dolor</a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="shop-cat-box">
                        <img class="img-fluid" src="images/categories_img_02.jpg" alt="" />
                        <a class="btn hvr-hover" href="#">Lorem ipsum dolor</a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="shop-cat-box">
                        <img class="img-fluid" src="images/categories_img_03.png" alt="" />
                        <a class="btn hvr-hover" href="#">Lorem ipsum dolor</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Categories -->
	
	<div class="box-add-products">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-12">
					<div class="offer-box-products">
						<img class="img-fluid" src="images/add-img-01.jpg" alt="" />
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12">
					<div class="offer-box-products">
						<img class="img-fluid" src="images/add-img-02.jpg" alt="" />
					</div>
				</div>
			</div>
		</div>
	</div>
   

        <!-- List of Kos -->
        <div class="products-box py-5">
            <div class="container">
                <div class="title-all text-center mb-4">
                    <h1>Daftar Kos</h1>
                    <p>Hasil pencarian berdasarkan kriteria Anda</p>
                </div>
                <div class="row">
                    @forelse($kosList as $kos)
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card h-100">
                                <img src="{{ asset('storage/'.($kos->image ?? 'default.jpg')) }}" class="card-img-top" alt="{{ $kos->nama_kos }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $kos->nama_kos }}</h5>
                                    <p class="card-text">{{ Str::limit($kos->deskripsi, 80) }}</p>
                                    <ul class="list-unstyled mb-2">
                                        <li><strong>Alamat:</strong> {{ $kos->alamat }}</li>
                                        <li><strong>Harga:</strong> Rp {{ number_format($kos->harga) }}/bln</li>
                                        <li><strong>Fasilitas:</strong> {{ $kos->fasilitas }}</li>
                                        <li><strong>Status:</strong> {{ $kos->status_ketersediaan ? 'Tersedia' : 'Penuh' }}</li>
                                    </ul>
                                    @isset($kos->distance)
                                        <p class="small text-muted">Jarak: {{ round($kos->distance,2) }} km</p>
                                    @endisset
                                    <a href="{{ route('kos.show', $kos->id_kos) }}" class="btn btn-primary btn-sm">Detail</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center">
                            <p>Tidak ada kos ditemukan sesuai kriteria.</p>
                        </div>
                    @endforelse
                </div>
                <div class="d-flex justify-content-center mt-4">
                    {{ $kosList->withQueryString()->links() }}
                </div>
            </div>
        </div>

    <!-- Start Products  -->
    <div class="products-box">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title-all text-center">
                        <h1>Fruits & Vegetables</h1>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sit amet lacus enim.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="special-menu text-center">
                        <div class="button-group filter-button-group">
                            <button class="active" data-filter="*">All</button>
                            <button data-filter=".top-featured">Top featured</button>
                            <button data-filter=".best-seller">Best seller</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                @forelse($kosList as $kos)
                    <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card h-100">
                        {{-- paket null-coalescing dengan benar --}}
                        <img src="{{ asset('storage/'.($kos->image ?? 'default.jpg')) }}"
                            class="card-img-top"
                            alt="{{ $kos->nama_kos }}">

                        <div class="card-body">
                        <h5 class="card-title">{{ $kos->nama_kos }}</h5>
                        <p class="card-text">{{ $kos->alamat }}</p>
                        <p class="card-text">Rp {{ number_format($kos->harga) }}/bln</p>
                        <p class="card-text">Fasilitas: {{ $kos->fasilitas }}</p>
                        <p class="card-text">
                            Status: {{ $kos->status_ketersediaan ? 'Tersedia' : 'Penuh' }}
                        </p>

                        @isset($kos->distance)
                            <p class="small text-muted">
                            Jarak: {{ round($kos->distance, 2) }} km
                            </p>
                        @endisset

                        <a href="{{ route('kos.show', ['id_kos' => $kos->id_kos]) }}"
                            class="btn btn-sm btn-outline-primary">
                            Detail
                        </a>
                        </div>
                    </div>
                    </div>
                @empty
                    <div class="col-12">
                    <p class="text-center">Kos tidak ditemukan untuk kriteria ini.</p>
                    </div>
                @endforelse
                </div>

                <div class="d-flex justify-content-center mt-4">
                {{ $kosList->withQueryString()->links() }}
                </div>

        </div>
    </div>
    <!-- End Products  -->

    <!-- Start Blog  -->
    <div class="latest-blog">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title-all text-center">
                        <h1>latest blog</h1>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sit amet lacus enim.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-4 col-xl-4">
                    <div class="blog-box">
                        <div class="blog-img">
                            <img class="img-fluid" src="images/blog-img.jpg" alt="" />
                        </div>
                        <div class="blog-content">
                            <div class="title-blog">
                                <h3>Fusce in augue non nisi fringilla</h3>
                                <p>Nulla ut urna egestas, porta libero id, suscipit orci. Quisque in lectus sit amet urna dignissim feugiat. Mauris molestie egestas pharetra. Ut finibus cursus nunc sed mollis. Praesent laoreet lacinia elit id lobortis.</p>
                            </div>
                            <ul class="option-blog">
                                <li><a href="#"><i class="far fa-heart"></i></a></li>
                                <li><a href="#"><i class="fas fa-eye"></i></a></li>
                                <li><a href="#"><i class="far fa-comments"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xl-4">
                    <div class="blog-box">
                        <div class="blog-img">
                            <img class="img-fluid" src="images/blog-img-01.jpg" alt="" />
                        </div>
                        <div class="blog-content">
                            <div class="title-blog">
                                <h3>Fusce in augue non nisi fringilla</h3>
                                <p>Nulla ut urna egestas, porta libero id, suscipit orci. Quisque in lectus sit amet urna dignissim feugiat. Mauris molestie egestas pharetra. Ut finibus cursus nunc sed mollis. Praesent laoreet lacinia elit id lobortis.</p>
                            </div>
                            <ul class="option-blog">
                                <li><a href="#"><i class="far fa-heart"></i></a></li>
                                <li><a href="#"><i class="fas fa-eye"></i></a></li>
                                <li><a href="#"><i class="far fa-comments"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xl-4">
                    <div class="blog-box">
                        <div class="blog-img">
                            <img class="img-fluid" src="images/blog-img-02.jpg" alt="" />
                        </div>
                        <div class="blog-content">
                            <div class="title-blog">
                                <h3>Fusce in augue non nisi fringilla</h3>
                                <p>Nulla ut urna egestas, porta libero id, suscipit orci. Quisque in lectus sit amet urna dignissim feugiat. Mauris molestie egestas pharetra. Ut finibus cursus nunc sed mollis. Praesent laoreet lacinia elit id lobortis.</p>
                            </div>
                            <ul class="option-blog">
                                <li><a href="#"><i class="far fa-heart"></i></a></li>
                                <li><a href="#"><i class="fas fa-eye"></i></a></li>
                                <li><a href="#"><i class="far fa-comments"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Blog  -->


    <!-- Start Instagram Feed  -->
    <div class="instagram-box">
        <div class="main-instagram owl-carousel owl-theme">
            <div class="item">
                <div class="ins-inner-box">
                    <img src="images/instagram-img-01.jpg" alt="" />
                    <div class="hov-in">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="ins-inner-box">
                    <img src="images/instagram-img-02.jpg" alt="" />
                    <div class="hov-in">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="ins-inner-box">
                    <img src="images/instagram-img-03.jpg" alt="" />
                    <div class="hov-in">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="ins-inner-box">
                    <img src="images/instagram-img-04.jpg" alt="" />
                    <div class="hov-in">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="ins-inner-box">
                    <img src="images/instagram-img-05.jpg" alt="" />
                    <div class="hov-in">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="ins-inner-box">
                    <img src="images/instagram-img-06.jpg" alt="" />
                    <div class="hov-in">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="ins-inner-box">
                    <img src="images/instagram-img-07.jpg" alt="" />
                    <div class="hov-in">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="ins-inner-box">
                    <img src="images/instagram-img-08.jpg" alt="" />
                    <div class="hov-in">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="ins-inner-box">
                    <img src="images/instagram-img-09.jpg" alt="" />
                    <div class="hov-in">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="ins-inner-box">
                    <img src="images/instagram-img-05.jpg" alt="" />
                    <div class="hov-in">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Instagram Feed  -->


    <!-- Start Footer  -->
    <footer>
        <div class="footer-main">
            <div class="container">
				<div class="row">
					<div class="col-lg-4 col-md-12 col-sm-12">
						<div class="footer-top-box">
							<h3>Business Time</h3>
							<ul class="list-time">
								<li>Monday - Friday: 08.00am to 05.00pm</li> <li>Saturday: 10.00am to 08.00pm</li> <li>Sunday: <span>Closed</span></li>
							</ul>
						</div>
					</div>
					<div class="col-lg-4 col-md-12 col-sm-12">
						<div class="footer-top-box">
							<h3>Newsletter</h3>
							<form class="newsletter-box">
								<div class="form-group">
									<input class="" type="email" name="Email" placeholder="Email Address*" />
									<i class="fa fa-envelope"></i>
								</div>
								<button class="btn hvr-hover" type="submit">Submit</button>
							</form>
						</div>
					</div>
					<div class="col-lg-4 col-md-12 col-sm-12">
						<div class="footer-top-box">
							<h3>Social Media</h3>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
							<ul>
                                <li><a href="#"><i class="fab fa-facebook" aria-hidden="true"></i></a></li>
                                <li><a href="#"><i class="fab fa-twitter" aria-hidden="true"></i></a></li>
                                <li><a href="#"><i class="fab fa-linkedin" aria-hidden="true"></i></a></li>
                                <li><a href="#"><i class="fab fa-google-plus" aria-hidden="true"></i></a></li>
                                <li><a href="#"><i class="fa fa-rss" aria-hidden="true"></i></a></li>
                                <li><a href="#"><i class="fab fa-pinterest-p" aria-hidden="true"></i></a></li>
                                <li><a href="#"><i class="fab fa-whatsapp" aria-hidden="true"></i></a></li>
                            </ul>
						</div>
					</div>
				</div>
				<hr>
                <div class="row">
                    <div class="col-lg-4 col-md-12 col-sm-12">
                        <div class="footer-widget">
                            <h4>About Freshshop</h4>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p> 
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p> 							
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12">
                        <div class="footer-link">
                            <h4>Information</h4>
                            <ul>
                                <li><a href="#">About Us</a></li>
                                <li><a href="#">Customer Service</a></li>
                                <li><a href="#">Our Sitemap</a></li>
                                <li><a href="#">Terms &amp; Conditions</a></li>
                                <li><a href="#">Privacy Policy</a></li>
                                <li><a href="#">Delivery Information</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12">
                        <div class="footer-link-contact">
                            <h4>Contact Us</h4>
                            <ul>
                                <li>
                                    <p><i class="fas fa-map-marker-alt"></i>Address: Michael I. Days 3756 <br>Preston Street Wichita,<br> KS 67213 </p>
                                </li>
                                <li>
                                    <p><i class="fas fa-phone-square"></i>Phone: <a href="tel:+1-888705770">+1-888 705 770</a></p>
                                </li>
                                <li>
                                    <p><i class="fas fa-envelope"></i>Email: <a href="mailto:contactinfo@gmail.com">contactinfo@gmail.com</a></p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- End Footer  -->

    <!-- Google Places API for Autocomplete -->
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places"></script>
    <script>
      function initAutocomplete() {
        const input = document.getElementById('location-input');
        const autocomplete = new google.maps.places.Autocomplete(input, { types: ['geocode'] });
        autocomplete.addListener('place_changed', () => {
          const place = autocomplete.getPlace();
          document.getElementById('loc_lat').value = place.geometry.location.lat();
          document.getElementById('loc_lng').value = place.geometry.location.lng();
        });
      }
      document.addEventListener('DOMContentLoaded', initAutocomplete);
    </script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="{{ URL::asset('js/custom.js') }}"></script>

    <!-- Start copyright  -->
    <div class="footer-copyright">
        <p class="footer-company">All Rights Reserved. &copy; 2018 <a href="#">ThewayShop</a> Design By :
            <a href="https://html.design/">html design</a></p>
    </div>
    <!-- End copyright  -->

    <a href="#" id="back-to-top" title="Back to top" style="display: none;">&uarr;</a>

    <!-- ALL JS FILES -->
    <script src="{{ URL::asset("js/jquery-3.2.1.min.js")}}"></script>
    <script src="{{ URL::asset("js/popper.min.js")}}"></script>
    <script src="{{ URL::asset("js/bootstrap.min.js")}}"></script>
    <!-- ALL PLUGINS -->
    <script src="{{ URL::asset("js/jquery.superslides.min.js")}}"></script>
    <script src="{{ URL::asset("js/bootstrap-select.js")}}"></script>
    <script src="{{ URL::asset("js/inewsticker.js")}}"></script>
    <script src="{{ URL::asset("js/bootsnav.js")}}"></script>
    <script src="{{ URL::asset("js/images-loded.min.js")}}"></script>
    <script src="{{ URL::asset("js/isotope.min.js")}}"></script>
    <script src="{{ URL::asset("js/owl.carousel.min.js")}}"></script>
    <script src="{{ URL::asset("js/baguetteBox.min.js")}}"></script>
    <script src="{{ URL::asset("js/form-validator.min.js")}}"></script>
    <script src="{{ URL::asset("js/contact-form-script.js")}}"></script>
    <script src="{{ URL::asset("js/custom.js")}}"></script>
</body>

</html>