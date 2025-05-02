@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h1 class="h3 mb-0">User Profiles</h1>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="mb-3 text-end">
                <a href="{{ route('user_profile.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-1"></i> Add New Profile
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Location</th>
                            <th>Price Range</th>
                            <th>Room Type</th>
                            <th>Facilities</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users_profile as $profile)
                            <tr>
                                <td>{{ $profile->name }}</td>
                                <td>
                                    <a href="mailto:{{ $profile->email }}">{{ $profile->email }}</a>
                                </td>
                                <td>{{ $profile->phone ?: '-' }}</td>
                                <td>{{ $profile->location ?: '-' }}</td>
                                <td>{{ $profile->price_range ?: '-' }}</td>
                                <td>{{ $profile->room_type ?: '-' }}</td>
                                <td>
                                    @if($profile->facilities)
                                        @php
                                            $facilitiesList = explode(',', $profile->facilities); 
                                            $count = count($facilitiesList);
                                        @endphp
                                        
                                        @if($count > 3)
                                            <span data-bs-toggle="tooltip" title="{{ implode(', ', $facilitiesList) }}">
                                                {{ $facilitiesList[0] }}, {{ $facilitiesList[1] }} 
                                                <span class="badge bg-secondary">+{{ $count - 2 }} more</span>
                                            </span>
                                        @else
                                            {{ $profile->facilities }}
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('user_profile.show', $profile->id) }}" class="btn btn-info btn-sm d-flex align-items-center gap-1" title="View">
                                            <i class="fas fa-eye"></i> <span>View</span>
                                        </a>
                                        <a href="{{ route('user_profile.edit', $profile->id) }}" class="btn btn-warning btn-sm d-flex align-items-center gap-1" title="Edit">
                                            <i class="fas fa-edit"></i> <span>Edit</span>
                                        </a>
                                        <form action="{{ route('user_profile.destroy', $profile->id) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm d-flex align-items-center gap-1" title="Delete" 
                                                    onclick="return confirm('Are you sure you want to delete this profile?')">
                                                <i class="fas fa-trash"></i> <span>Delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="alert alert-info mb-0">
                                        No profiles found. <a href="{{ route('user_profile.create') }}">Create one now</a>.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
@endsection