@extends('layouts.navbar')

@section('content')
<div class="container py-4">
    <h3 class="mb-4">Semua Notifikasi</h3>

    <ul class="list-group">
        @forelse ($notifications as $notification)
            <li class="list-group-item d-flex justify-content-between align-items-start" id="notif-{{ $notification->id }}">
                <div>
                    <strong>{{ $notification->data['sender_name'] ?? 'Notifikasi' }}</strong>: 
                    {{ $notification->data['message'] ?? '' }} <br>
                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                </div>
                <button class="btn btn-sm btn-link text-danger delete-notification" data-id="{{ $notification->id }}">&times;</button>
            </li>
        @empty
            <li class="list-group-item text-muted">Tidak ada notifikasi.</li>
        @endforelse
    </ul>
</div>
@endsection
