@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create User Profile</h1>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('user_profile.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('user_profile.form')
    </form>
</div>
@endsection