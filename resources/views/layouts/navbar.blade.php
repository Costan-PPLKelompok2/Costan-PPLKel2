<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Site Metas -->
    <title>Cost'an</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ URL::asset('css/bootstrap.min.css') }}">
    <!-- Site CSS -->
    <link rel="stylesheet" href="{{ URL::asset('css/style.css') }}">

    <script src="//unpkg.com/alpinejs" defer></script>
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-default bootsnav">
        <div class="container">
            <!-- Start Header Navigation -->
            <div class="navbar-header">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-menu" aria-controls="navbars-rs-food" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa fa-bars"></i>
            </button>
                <a class="navbar-brand" href="{{route('redirect')}}"><img src="{{asset('images/logo_costan.png')}}" class="logo" alt="logo"></a>
            </div>
            <!-- End Header Navigation -->

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="navbar-menu">
                <ul class="nav navbar-nav ml-auto" data-in="fadeInDown" data-out="fadeOutUp">
                    <li class="nav-item {{ Route::is('home.index') ? 'active' : '' }}"><a class="nav-link" href="{{route('redirect')}}">Home</a></li>
                    <li class="nav-item {{ Route::is('home.daftarkos') ? 'active' : '' }}"><a class="nav-link" href="{{route('home.daftarkos')}}">Daftar Kos</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Favorite</a></li>
                    <!-- <li class="dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Profile</a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('user_profile.index') }}">My Account</a></li>
                            <li><a href="#">Favorite</a></li>
                        </ul>
                    </li> -->
                </ul>
            </div>         

            <div class="attr-nav">
                <ul>
                    <li class="search"><a href="#"><i class="fa fa-search"></i></a></li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->

            <!-- Start Atribute Navigation -->
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav ml-auto" data-in="fadeInDown" data-out="fadeOutUp">
                @if (Route::has('login'))
                    @auth
                        <li class="nav-item dropdown">
                            <a class="d-flex align-items-center justify-content-center nav-link dropdown-toggle border border-secondary rounded px-3 py-1" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                {{ Auth::user()->name }}
                                @php
                                    $user = Auth::user();
                                    $initial = strtoupper(substr($user->name, 0, 1));
                                @endphp

                                @if ($user->foto)
                                    <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto Profil" class="rounded-circle" width="35" height="35" style="object-fit: cover; margin-left: 10px;">
                                @else
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; font-weight: bold; margin-left: 10px;">
                                        {{ $initial }}
                                    </div>
                                @endif
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                                <span class="dropdown-item-text text-muted small pl-3">
                                    {{ __('Manage Account') }}
                                </span>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('profile.show') }}">{{ __('Profile') }}</a>

                                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                    <a class="dropdown-item" href="{{ route('api-tokens.index') }}">{{ __('API Tokens') }}</a>
                                @endif

                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item" type="submit">{{ __('Log Out') }}</button>
                                </form>
                            </div>
                        </li>
                    @else

                        <div class="collapse navbar-collapse">
                            <ul class="nav navbar-nav ml-auto" data-in="fadeInDown" data-out="fadeOutUp">
                                <li class="nav-item"><a href="{{ route('login') }}">Log in</a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}">Register</a>
                                    @endif
                                </li>
                            </ul>
                        </div> 
                    @endauth
                @endif
            </div>
        </div>
    </nav>
    <div class="min-h-screen bg-gray-100">
        <!-- Page Content -->
        <main class="container-fluid">
            @yield('content')
            {{ $slot ?? '' }}
        </main>
    </div>

    
    <!-- Start Footer  -->
    @include('home.footer')
    <!-- End Footer  -->

    <!-- Start copyright  -->
    <div class="footer-copyright">
        <p class="footer-company">All Rights Reserved. &copy; 2025 Cost'an Design By :
            PPL Kelompok 2</p>
    </div>
    <!-- End copyright  -->

    <a href="#" id="back-to-top" title="Back to top" style="">&uarr;</a>

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