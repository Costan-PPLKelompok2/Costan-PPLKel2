{{-- resources/views/chat/index.blade.php --}}
@extends('layouts.app') {{-- Sesuaikan dengan layout utama Anda --}}

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Kotak Masuk</h1>
        {{-- Optional: Add a "New Message" button or search bar here --}}
    </div>

    @if($chatRooms->isEmpty())
        <div class="text-center py-16 bg-white shadow-md rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
            <p class="mt-5 text-xl font-semibold text-gray-700">Kotak Masuk Anda Kosong</p>
            <p class="text-gray-500 mt-2">Sepertinya belum ada percakapan. <br>Mulai chat dengan pemilik atau penyewa kos!</p>

            <div class="mt-8">
                <a href="{{ route('kos.search') }}"
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Cari Kos Sekarang
                </a>
            </div>
        </div>
    @else
        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <ul class="divide-y divide-gray-200">
                @foreach ($chatRooms as $room)
                    @php
                        // Ensure $user is available in this context, or pass $currentUser from controller
                        $currentLoggedInUser = $user ?? auth()->user();
                        $otherParticipant = $room->tenant_id == $currentLoggedInUser->id ? $room->owner : $room->tenant;
                        $unreadCount = $room->unreadMessagesCount(); // Pastikan method ini ada di model ChatRoom dan considers the current user
                    @endphp
                    <li class="hover:bg-gray-50 transition duration-150 ease-in-out {{ $unreadCount > 0 ? 'bg-blue-50' : '' }}">
                        <a href="{{ route('chat.show', $room->id) }}" class="block p-4 md:p-5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 rounded-md">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center min-w-0"> {{-- min-w-0 untuk ellipsis/truncate --}}
                                    <div class="relative mr-3 md:mr-4 flex-shrink-0">
                                        <img class="h-12 w-12 md:h-14 md:w-14 rounded-full object-cover ring-2 ring-white"
                                             src="{{ $otherParticipant->profile_photo_url ?? asset('images/default-avatar.png') }}"
                                             alt="Foto {{ $otherParticipant->name }}">
                                        {{-- Indikator online (jika ada logikanya)
                                        <span class="absolute bottom-0 right-0 block h-3.5 w-3.5 rounded-full bg-green-500 border-2 border-white"></span>
                                        --}}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center">
                                            <p class="text-md md:text-lg font-semibold text-gray-800 truncate">
                                                {{ $otherParticipant->name }}
                                            </p>
                                            @if($unreadCount > 0)
                                                <span class="ml-2 inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
                                                    {{ $unreadCount }}
                                                </span>
                                            @endif
                                        </div>
                                        @if ($room->latestMessage)
                                            <p class="text-sm text-gray-600 truncate mt-1 {{ $unreadCount > 0 ? 'font-semibold' : '' }}">
                                                @if($room->latestMessage->sender_id == $currentLoggedInUser->id)
                                                    <span class="font-medium text-gray-500">Anda:</span>
                                                @endif
                                                {{ Str::limit($room->latestMessage->body, 40) }}
                                            </p>
                                        @else
                                            <p class="text-sm text-gray-500 italic mt-1">Belum ada pesan.</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right ml-2 flex-shrink-0">
                                    @if ($room->latestMessage)
                                        <p class="text-xs md:text-sm text-gray-500 mb-1 whitespace-nowrap">
                                            {{ $room->latestMessage->created_at->isToday() ? $room->latestMessage->created_at->format('H:i') : $room->latestMessage->created_at->isoFormat('D MMM') }}
                                        </p>
                                    @else
                                         <p class="text-xs md:text-sm text-gray-400 mb-1 whitespace-nowrap">
                                            {{ $room->updated_at->isToday() ? $room->updated_at->format('H:i') : $room->updated_at->isoFormat('D MMM') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        @if ($chatRooms->hasPages())
        <div class="mt-8">
            {{-- Pastikan view paginasi Anda sudah di-publish dan di-style dengan Tailwind --}}
            {{-- php artisan vendor:publish --tag=laravel-pagination --}}
            {{ $chatRooms->links() }}
        </div>
        @endif
    @endif
</div>
@endsection