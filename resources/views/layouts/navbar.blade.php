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
                            <a class="nav-link dropdown-toggle bg-secondary text-white rounded px-3 py-1" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ Auth::user()->name }}
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
                                    <button type="submit" class="dropdown-item">{{ __('Log Out') }}</button>
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
</body>