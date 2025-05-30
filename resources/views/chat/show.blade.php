{{-- resources/views/chat/show.blade.php --}}
@extends('layouts.app') {{-- Sesuaikan dengan layout utama Anda --}}

@section('content')
<div class="container mx-auto px-2 py-4 md:px-4 md:py-8 flex flex-col h-[calc(100vh-100px)]"> {{-- Sesuaikan tinggi --}}
    @php
        $otherParticipant = $chatRoom->tenant_id == $currentUser->id ? $chatRoom->owner : $chatRoom->tenant;
    @endphp
    <div class="bg-white shadow-sm rounded-t-lg p-4 border-b border-gray-200">
        <div class="flex items-center">
            <a href="{{ route('chat.index') }}" class="text-blue-600 hover:text-blue-800 mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <img class="h-10 w-10 rounded-full object-cover mr-2" 
                 src="{{ $otherParticipant->profile_photo_url ?? asset('images/default-avatar.png') }}" 
                 alt="Foto {{ $otherParticipant->name }}">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">{{ $otherParticipant->name }}</h2>
                @if($chatRoom->kos)
                    <p class="text-sm text-gray-600">{{ $chatRoom->kos->nama_kos }}</p>
                @endif
            </div>
        </div>
    </div>

    <div id="messageArea" class="flex-grow bg-gray-50 p-4 overflow-y-auto space-y-4">
        @forelse ($messages as $message)
            <div class="flex {{ $message->sender_id == $currentUser->id ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg shadow {{ $message->sender_id == $currentUser->id ? 'bg-blue-500 text-gray' : 'bg-gray-200 text-gray-800' }}">
                    @if($message->sender_id != $currentUser->id)
                    <p class="text-xs font-semibold mb-1 {{ $message->sender_id == $currentUser->id ? 'text-blue-100' : 'text-gray-600' }}">{{ $message->sender->name }}</p>
                    @endif
                    <p class="text-sm break-words">{{ $message->body }}</p>
                    <p class="text-xs mt-1 {{ $message->sender_id == $currentUser->id ? 'text-blue-200' : 'text-gray-500' }} text-right">
                        {{ $message->created_at->format('H:i') }}
                        @if ($message->sender_id == $currentUser->id && $message->read_at)
                            <span title="Dibaca pada {{ $message->read_at->format('d M H:i') }}">✓✓</span>
                        @elseif ($message->sender_id == $currentUser->id)
                            <span title="Terkirim">✓</span>
                        @endif
                    </p>
                </div>
            </div>
        @empty
            <p class="text-center text-gray-500">Belum ada pesan di chat ini. Mulai percakapan!</p>
        @endforelse
    </div>

    <div class="bg-white shadow-t-sm rounded-b-lg p-4 border-t border-gray-200">
        <form action="{{ route('chat.store', $chatRoom) }}" method="POST" id="sendMessageForm">
            @csrf
            <div class="flex items-center">
                <input type="text" name="body" id="messageInput"
                       class="w-3/4 border border border-gray-300 rounded-l-md py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="Ketik pesan Anda..." autocomplete="off">
                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-black font-semibold py-3 px-6 rounded-r-md transition duration-150">
                    Kirim
                </button>
            </div>
            @error('body')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const messageArea = document.getElementById('messageArea');
        // Auto scroll ke bawah
        messageArea.scrollTop = messageArea.scrollHeight;

        // Untuk AJAX (opsional, dasar polling)
        const sendMessageForm = document.getElementById('sendMessageForm');
        const messageInput = document.getElementById('messageInput');

        // Jika Anda ingin chat lebih real-time tanpa full page reload,
        // Anda bisa implementasikan pengiriman via AJAX dan polling pesan baru.
        // Contoh sederhana AJAX submit (bisa dikembangkan dengan polling atau WebSockets):
        /*
        sendMessageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // Pastikan ada meta csrf-token di layout
                    'Accept': 'application/json', // Penting agar controller mengembalikan JSON
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success && data.message) {
                    messageInput.value = ''; // Kosongkan input
                    // Tambahkan pesan baru ke UI secara dinamis
                    const newMessageDiv = document.createElement('div');
                    newMessageDiv.classList.add('flex', 'justify-end'); // Asumsi pengirim adalah user saat ini
                    newMessageDiv.innerHTML = `
                        <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg shadow bg-blue-500 text-white">
                            <p class="text-sm break-words">${data.message.body}</p>
                            <p class="text-xs mt-1 text-blue-200 text-right">
                                ${new Date(data.message.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}
                                <span title="Terkirim">✓</span>
                            </p>
                        </div>
                    `;
                    messageArea.appendChild(newMessageDiv);
                    messageArea.scrollTop = messageArea.scrollHeight; // Scroll ke bawah
                } else {
                    // Handle error
                    alert('Gagal mengirim pesan.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan.');
            });
        });

        // Untuk memuat pesan baru secara periodik (polling sederhana)
        // setInterval(function() {
        //    fetch(window.location.href, { headers: {'Accept': 'text/html'} }) // atau endpoint khusus JSON
        //        .then(response => response.text())
        //        .then(html => {
        //            const newDoc = new DOMParser().parseFromString(html, 'text/html');
        //            const newMessages = newDoc.getElementById('messageArea').innerHTML;
        //            if (messageArea.innerHTML !== newMessages) {
        //                messageArea.innerHTML = newMessages;
        //                messageArea.scrollTop = messageArea.scrollHeight;
        //            }
        //        });
        // }, 5000); // Cek setiap 5 detik
        */
    });
</script>
@endpush
@endsection