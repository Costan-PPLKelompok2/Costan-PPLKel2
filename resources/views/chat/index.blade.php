{{-- resources/views/chat/index.blade.php --}}
@extends('layouts.app') {{-- Sesuaikan dengan layout utama Anda --}}

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Kotak Masuk Pesan</h1>

    @if($chatRooms->isEmpty())
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
            <p class="font-bold">Informasi</p>
            <p>Anda belum memiliki percakapan.</p>
        </div>
    @else
        <div class="bg-gray shadow-md rounded-lg overflow-hidden">
            <ul class="divide-y divide-gray-200">
                @foreach ($chatRooms as $room)
                    @php
                        $otherParticipant = $room->tenant_id == $user->id ? $room->owner : $room->tenant;
                        $unreadCount = $room->unreadMessagesCount();
                    @endphp
                    <li class="hover:bg-gray-50 transition duration-150 ease-in-out">
                        <a href="{{ route('chat.show', $room->id) }}" class="block p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img class="h-12 w-12 rounded-full object-cover mr-2" 
                                         src="{{ $otherParticipant->profile_photo_url ?? asset('images/default-avatar.png') }}" {{-- Ganti dengan path default avatar Anda --}}
                                         alt="Foto {{ $otherParticipant->name }}">
                                    <div>
                                        <p class="text-lg font-semibold text-gray-900">
                                            {{ $otherParticipant->name }}
                                            @if($room->kos)
                                                <span class="text-sm text-gray-500">- {{ $room->kos->nama_kos }}</span>
                                            @endif
                                        </p>
                                        @if ($room->latestMessage)
                                        <p class="text-sm text-gray-600 truncate max-w-xs">
                                            @if($room->latestMessage->sender_id == $user->id)
                                                <span class="font-medium">Anda:</span>
                                            @endif
                                            {{ Str::limit($room->latestMessage->body, 50) }}
                                        </p>
                                        @else
                                        <p class="text-sm text-gray-500 italic">Belum ada pesan.</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if ($room->latestMessage)
                                    <p class="text-xs text-gray-400 mb-1">
                                        {{ $room->latestMessage->created_at->diffForHumans() }}
                                    </p>
                                    @endif
                                    @if ($unreadCount > 0)
                                        <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
                                            {{ $unreadCount }}
                                        </span>
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
            {{ $chatRooms->links() }}
        </div>
        @endif
    @endif
</div>
@endsection