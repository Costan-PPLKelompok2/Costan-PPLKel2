@props(['user'])

<div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-bell"></i>
        @if($user->unreadNotifications->count() > 0)
            <span class="badge bg-danger">{{ $user->unreadNotifications->count() }}</span>
        @endif
    </button>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" style="width: 300px; max-height: 400px; overflow-y: auto;">
        @if($user->notifications->count() > 0)
            @foreach($user->notifications as $notification)
                <li>
                    <a class="dropdown-item {{ $notification->read_at ? '' : 'fw-bold bg-light' }}" 
                       href="{{ route('chat.show', ['kost' => $notification->data['kost_id'], 'user_profile_id' => $user->id]) }}"
                       onclick="markNotificationAsRead('{{ $notification->id }}', '{{ $user->id }}', event)">
                        <div class="d-flex flex-column">
                            <div class="small text-muted">{{ $notification->created_at->diffForHumans() }}</div>
                            <div>Pesan baru dari {{ $notification->data['sender_name'] }} tentang {{ $notification->data['kost_name'] }}</div>
                            <div class="text-truncate">{{ $notification->data['message_preview'] }}</div>
                        </div>
                    </a>
                </li>
            @endforeach
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item text-center" href="#" onclick="markAllNotificationsAsRead('{{ $user->id }}', event)">
                    Tandai semua sudah dibaca
                </a>
            </li>
        @else
            <li><a class="dropdown-item text-center" href="#">Tidak ada notifikasi</a></li>
        @endif
    </ul>
</div>

@push('scripts')
<script>
    function markNotificationAsRead(notificationId, userId, event) {
        event.preventDefault();
        
        fetch('{{ route('notifications.mark-read') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                notification_id: notificationId,
                user_profile_id: userId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = event.currentTarget.href;
            }
        });
    }
    
    function markAllNotificationsAsRead(userId, event) {
        event.preventDefault();
        
        fetch('{{ route('notifications.mark-read') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                user_profile_id: userId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        });
    }
</script>
@endpush