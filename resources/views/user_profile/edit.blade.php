@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit User Profile</h1>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('user_profile.update', $users_profile->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('user_profile.form', ['profile' => $users_profile])
    </form>
</div>
@endsection