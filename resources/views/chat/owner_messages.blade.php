@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Pesan untuk') }}: {{ $kost->name }}</span>
                    <a href="{{ route('chat.owner', ['owner_id' => $owner->id]) }}" class="btn btn-sm btn-secondary">Kembali</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>Daftar Penanya</h5>
                            <div class="list-group">
                                @forelse($inquirers as $inquirer)
                                    @php
                                        $unreadCount = App\Models\Message::where('kost_id', $kost->id)
                                            ->where('sender_id', $inquirer->id)
                                            ->where('receiver_id', $owner->id)
                                            ->whereDoesntHave('receiver.notifications', function($q) {
                                                $q->whereNotNull('read_at');
                                            })
                                            ->count();
                                            
                                        $lastMessage = App\Models\Message::where('kost_id', $kost->id)
                                            ->where(function($q) use ($inquirer, $owner) {
                                                $q->where('sender_id', $inquirer->id)
                                                  ->where('receiver_id', $owner->id)
                                                  ->orWhere('sender_id', $owner->id)
                                                  ->where('receiver_id', $inquirer->id);
                                            })
                                            ->latest()
                                            ->first();
                                    @endphp
                                    <a href="#" class="list-group-item list-group-item-action inquirer-item" data-inquirer-id="{{ $inquirer->id }}">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">{{ $inquirer->name }}</h6>
                                            @if($unreadCount > 0)
                                                <span class="badge bg-danger">{{ $unreadCount }} baru</span>
                                            @endif
                                        </div>
                                        @if($lastMessage)
                                            <p class="mb-1 text-muted small">
                                                {{ Str::limit($lastMessage->message, 50) }}
                                            </p>
                                            <small class="text-muted">{{ $lastMessage->created_at->diffForHumans() }}</small>
                                        @else
                                            <p class="mb-1 text-muted small">Belum ada pesan</p>
                                        @endif
                                    </a>
                                @empty
                                    <div class="alert alert-info">
                                        Belum ada calon penyewa yang menghubungi Anda tentang kos ini.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="row" id="chatSection" style="display: none;">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <span>Percakapan dengan <span id="inquirerName"></span></span>
                                </div>
                                <div class="card-body">
                                    <div class="chat-history p-3 mb-4" id="messageContainer" style="max-height: 400px; overflow-y: auto; border: 1px solid #ddd; border-radius: 5px;">
                                        <!-- Messages will be loaded here -->
                                    </div>

                                    <form id="replyForm" action="{{ route('chat.send') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="kost_id" value="{{ $kost->id }}">
                                        <input type="hidden" name="sender_id" value="{{ $owner->id }}">
                                        <input type="hidden" name="receiver_id" id="receiver_id" value="">
                                        
                                        <div class="form-group">
                                            <label for="message">Balas Pesan:</label>
                                            <textarea name="message" class="form-control @error('message') is-invalid @enderror" rows="3" required>{{ old('message') }}</textarea>
                                            @error('message')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary mt-2">Kirim Balasan</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inquirerItems = document.querySelectorAll('.inquirer-item');
        const chatSection = document.getElementById('chatSection');
        const inquirerNameSpan = document.getElementById('inquirerName');
        const receiverIdInput = document.getElementById('receiver_id');
        const messageContainer = document.getElementById('messageContainer');

        inquirerItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Mark all items as inactive
                inquirerItems.forEach(i => i.classList.remove('active'));
                
                // Mark this item as active
                this.classList.add('active');
                
                // Get the inquirer data
                const inquirerId = this.getAttribute('data-inquirer-id');
                const inquirerName = this.querySelector('h6').textContent;
                
                // Update the chat section
                inquirerNameSpan.textContent = inquirerName;
                receiverIdInput.value = inquirerId;
                chatSection.style.display = 'block';
                
                // Load messages
                loadMessages(inquirerId);
                
                // Update any unread badge
                const badge = this.querySelector('.badge');
                if (badge) {
                    badge.remove();
                }
            });
        });

        function loadMessages(inquirerId) {
            fetch(`{{ route('chat.api.messages') }}?kost_id={{ $kost->id }}&owner_id={{ $owner->id }}&inquirer_id=${inquirerId}`)
                .then(response => response.json())
                .then(data => {
                    // Clear previous messages
                    messageContainer.innerHTML = '';
                    
                    if (data.messages.length === 0) {
                        messageContainer.innerHTML = '<div class="text-center text-muted">Belum ada percakapan. Mulai chat sekarang!</div>';
                        return;
                    }
                    
                    // Add messages
                    data.messages.forEach(message => {
                        const isOwner = message.sender_id == {{ $owner->id }};
                        const messageDiv = document.createElement('div');
                        messageDiv.className = `mb-3 d-flex ${isOwner ? 'justify-content-end' : 'justify-content-start'}`;
                        messageDiv.innerHTML = `
                            <div class="p-3 rounded" style="max-width: 80%; background-color: ${isOwner ? '#dcf8c6' : '#f1f0f0'};">
                                <div class="text-small">
                                    <strong>${message.sender.name}</strong>
                                </div>
                                <div>${message.message}</div>
                                <div class="text-muted text-right" style="font-size: 0.8rem;">
                                    ${formatDate(message.created_at)}
                                </div>
                            </div>
                        `;
                        messageContainer.appendChild(messageDiv);
                    });
                    
                    // Scroll to bottom
                    messageContainer.scrollTop = messageContainer.scrollHeight;
                    
                    // Mark messages as read
                    markMessagesAsRead(inquirerId);
                })
                .catch(error => {
                    console.error('Error loading messages:', error);
                    messageContainer.innerHTML = '<div class="text-center text-danger">Error loading messages. Please try again.</div>';
                });
        }
        
        function markMessagesAsRead(inquirerId) {
            fetch('{{ route('notifications.mark-read') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    user_profile_id: {{ $owner->id }},
                    inquirer_id: inquirerId,
                    kost_id: {{ $kost->id }}
                })
            });
        }
        
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleString('id-ID', {
                day: 'numeric',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    });
</script>
@endpush
@endsection