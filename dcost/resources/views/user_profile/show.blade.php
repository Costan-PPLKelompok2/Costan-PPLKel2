@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h1 class="h3 mb-0">Profile Details</h1>
                    <a href="{{ route('user_profile.index') }}" class="btn btn-sm btn-light">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>
                
                <div class="card-body">
                    <div class="text-center mb-4">
                        @if($users_profile->photo)
                            <img src="{{ asset('storage/' . $users_profile->photo) }}" alt="{{ $users_profile->name }}" 
                                class="img-thumbnail rounded-circle mb-3" style="width: 180px; height: 180px; object-fit: cover;">
                        @else
                            <div class="avatar-placeholder rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center bg-secondary text-white"
                                style="width: 180px; height: 180px; font-size: 64px;">
                                {{ strtoupper(substr($users_profile->name, 0, 1)) }}
                            </div>
                        @endif
                        <h2 class="h4 mb-0">{{ $users_profile->name }}</h2>
                        <p class="text-muted">{{ $users_profile->email }}</p>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h3 class="h5 border-bottom pb-2">Contact Information</h3>
                                <div class="mb-2">
                                    <strong><i class="fas fa-phone me-2"></i>Phone:</strong> 
                                    {{ $users_profile->phone ?: 'Not provided' }}
                                </div>
                                <div class="mb-2">
                                    <strong><i class="fas fa-map-marker-alt me-2"></i>Address:</strong>
                                    {{ $users_profile->address ?: 'Not provided' }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h3 class="h5 border-bottom pb-2">Preferences</h3>
                                <div class="mb-2">
                                    <strong><i class="fas fa-money-bill me-2"></i>Price Range:</strong> 
                                    {{ $users_profile->price_range ?: 'Not specified' }}
                                </div>
                                <div class="mb-2">
                                    <strong><i class="fas fa-map me-2"></i>Location:</strong>
                                    {{ $users_profile->location ?: 'Not specified' }}
                                </div>
                                <div class="mb-2">
                                    <strong><i class="fas fa-home me-2"></i>Room Type:</strong>
                                    {{ $users_profile->room_type ?: 'Not specified' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h3 class="h5 border-bottom pb-2">Preferred Facilities</h3>
                        @if($users_profile->facilities)
                            <div class="row">
                                @foreach(explode(',', $users_profile->facilities) as $facility)
                                    <div class="col-md-4 mb-2">
                                        <span class="badge bg-light text-dark border p-2">
                                            <i class="fas fa-check-circle text-success me-1"></i> {{ $facility }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">No facilities selected</p>
                        @endif
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('user_profile.edit', $users_profile->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i> Edit Profile
                        </a>
                        <form action="{{ route('user_profile.destroy', $users_profile->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this profile?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash me-1"></i> Delete Profile
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection