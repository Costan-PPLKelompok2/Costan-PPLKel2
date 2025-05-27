// File: resources/views/kos/detail.blade.php (tambahan untuk tombol chat)
<!-- Tambahkan ini di bagian detail kos -->
@if(auth()->check() && auth()->id() != $kos->user_id)
<div class="mt-6 p-6 bg-blue-50 rounded-lg">
    <h3 class="text-lg font-semibold mb-4">Tertarik dengan kos ini?</h3>
    <div class="flex flex-col sm:flex-row gap-4">
        @if($existingChatRoom)
            <a href="{{ route('chat.room', $existingChatRoom->id) }}" 
               class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition text-center">
                <i class="fas fa-comments mr-2"></i>
                Lanjutkan Chat
            </a>
        @else
            <form action="{{ route('kos.initiate-chat', $kos->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                        class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-comments mr-2"></i>
                    Chat dengan Pemilik
                </button>
            </form>
        @endif
        
        <a href="tel:{{ $kos->user->phone ?? '' }}" 
           class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition text-center">
            <i class="fas fa-phone mr-2"></i>
            Hubungi via Telepon
        </a>
    </div>
    
    <div class="mt-4 text-sm text-gray-600">
        <p><strong>Pemilik:</strong> {{ $kos->user->name }}</p>
        @if($kos->user->phone)
            <p><strong>Telepon:</strong> {{ $kos->user->phone }}</p>
        @endif
    </div>
</div>
@elseif(!auth()->check())
<div class="mt-6 p-6 bg-yellow-50 border border-yellow-200 rounded-lg">
    <h3 class="text-lg font-semibold mb-2">Ingin menghubungi pemilik?</h3>
    <p class="text-gray-600 mb-4">Silakan login terlebih dahulu untuk dapat mengirim pesan kepada pemilik kos.</p>
    <a href="{{ route('login') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
        <i class="fas fa-sign-in-alt mr-2"></i>
        Login untuk Chat
    </a>
</div>
@endif
