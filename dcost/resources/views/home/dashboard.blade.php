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
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control" placeholder="Search">
                <span class="input-group-addon close-search"><i class="fa fa-times"></i></span>
            </div>
        </div>
    </div>
    <!-- End Top Search -->

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
                            <!-- Button for search, conditionally link to login/register or search -->
                            <p>
                                @guest
                                    <a class="btn hvr-hover" href="{{ route('register') }}">Login untuk Cari Kos Anda</a>
                                @else
                                    <a class="btn hvr-hover" href="{{ route('login') }}">Cari Kos Anda</a> <!-- Gantilah 'search' dengan route pencarian kos Anda -->
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
                                    <a class="btn hvr-hover" href="{{ route('register') }}">Login untuk Cari Kos Anda</a>
                                @else
                                    <a class="btn hvr-hover" href="{{ route('login') }}">Cari Kos Anda</a>
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
                                    <a class="btn hvr-hover" href="{{ route('register') }}">Login untuk Cari Kos Anda</a>
                                @else
                                    <a class="btn hvr-hover" href="{{ route('login') }}">Cari Kos Anda</a>
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
    <!-- End Slider -->


    <!-- Start Categories  -->
    @include('home.populer')
    <!-- End Categories -->
	
    <!-- Start Offer -->
	<!-- <div class="box-add-products">
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
	</div> -->
    <!-- End Offer -->

    <!-- Start Products  -->
    @include('home.kos')
    <!-- End Products  -->

    <!-- Start Blog  -->
    @include('home.blog')
    <!-- End Blog  -->

    <!-- Register and Login Buttons (if not authenticated) -->
    @guest
        <div class="auth-buttons">
            <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
            <a href="{{ route('register') }}" class="btn btn-secondary">Register</a>
        </div>
    @else
        <!-- If authenticated, show user profile management -->
        <div class="user-profile">
            <p>Welcome, {{ Auth::user()->name }}!</p>
            <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profile</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>
        </div>
    @endguest

    <!-- Start Instagram Feed  -->
    @include('home.feed')
    <!-- End Instagram Feed  -->


    <!-- Start Footer  -->
    @include('home.footer')
    <!-- End Footer  -->

    <!-- Start copyright  -->
    <div class="footer-copyright">
        <p class="footer-company">All Rights Reserved. &copy; 2025 <a href="#">Cost'an</a> Design By :
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