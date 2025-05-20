@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Chat dengan Pemilik Kos') }}: {{ $kost->name }}</span>
                    <a href="{{ route('chat.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-3">
                        <h5>Informasi Kos</h5>
                        <p><strong>Nama:</strong> {{ $kost->name }}</p>
                        <p><strong>Alamat:</strong> {{ $kost->address }}</p>
                        <p><strong>Harga:</strong> Rp {{ number_format($kost->price, 0, ',', '.') }}</p>
                        <p><strong>Pemilik:</strong> {{ $kost->owner->name }}</p>
                        <p><strong>Deskripsi:</strong> {{ $kost->description }}</p>
                    </div>

                    <hr>

                    <h5>Riwayat Percakapan</h5>
                    <div class="chat-history p-3 mb-2" style="max-height: 400px; overflow-y: auto; border: 1px solid #ddd; border-radius: 5px;">
                        @if($messages->isEmpty())
                            <div class="text-center text-muted">
                                Belum ada percakapan. Mulai chat dengan pemilik kos sekarang!
                            </div>
                        @else
                            @foreach($messages as $message)
                                <div class="mb-3 d-flex {{ $message->sender_id == $users_profile->id ? 'justify-content-end' : 'justify-content-start' }}">
                                    <div class="p-3 rounded" 
                                         style="max-width: 80%; background-color: {{ $message->sender_id == $users_profile->id ? '#dcf8c6' : '#f1f0f0' }};">
                                        <div class="text-small">
                                            <strong>{{ $message->sender->name }}</strong>
                                        </div>
                                        <div>{{ $message->message }}</div>
                                        <div class="text-muted text-right" style="font-size: 0.8rem;">
                                            {{ $message->created_at->format('d M Y H:i') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <!-- Typing status -->
                    <div id="typing-status" class="text-muted mb-2" style="display: none;">
                        Pemilik sedang mengetik...
                    </div>

                    <!-- Form Kirim Pesan -->
                    <form action="{{ route('chat.send') }}" method="POST">
                        @csrf
                        <input type="hidden" name="kost_id" value="{{ $kost->id }}">
                        <input type="hidden" name="sender_id" value="{{ $users_profile->id }}">
                        <input type="hidden" name="receiver_id" value="{{ $kost->owner_id }}">
                        
                        <div class="form-group">
                            <label for="message">Pesan:</label>
                            <textarea name="message" class="form-control @error('message') is-invalid @enderror" rows="3" required>{{ old('message') }}</textarea>
                            @error('message')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-primary mt-2">Kirim Pesan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Scroll to bottom on load
    document.addEventListener('DOMContentLoaded', function () {
        var chatHistory = document.querySelector('.chat-history');
        if(chatHistory){
            chatHistory.scrollTop = chatHistory.scrollHeight;
        }

        // Disable button after submit
        const form = document.querySelector('form');
        const submitButton = form.querySelector('button[type="submit"]');
        form.addEventListener('submit', () => {
            submitButton.disabled = true;
            submitButton.innerText = 'Mengirim...';
        });

        // Typing status
        const messageInput = document.querySelector('textarea[name="message"]');
        const typingStatus = document.getElementById('typing-status');

        messageInput.addEventListener('input', function () {
            typingStatus.style.display = 'block';
            clearTimeout(window.typingTimer);
            window.typingTimer = setTimeout(() => {
                typingStatus.style.display = 'none';
            }, 1500);
        });
    });
</script>
@endpush
