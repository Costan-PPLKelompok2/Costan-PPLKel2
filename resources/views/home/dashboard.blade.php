@extends('layouts.navbar')
@section('content')

<!DOCTYPE html>
<html lang="en">
<!-- Basic -->
<body>
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
    @include('home.slider')
    <!-- End Slider -->


    <!-- Start Categories  -->
    @include('home.populer')
    <!-- End Categories -->

    <!-- Start Blog  -->
    <!-- @include('home.blog') -->
    <!-- End Blog  -->

    <!-- Start Instagram Feed  -->
    <!-- @include('home.feed') -->
    <!-- End Instagram Feed  -->
</body>

</html>

@endsection