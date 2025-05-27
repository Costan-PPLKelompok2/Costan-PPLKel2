// File: resources/views/chat/room.blade.php
@extends('layouts.app')

@section('title', 'Chat - ' . $chatRoom->kos->nama_kos)

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden h-[calc(100vh-8rem)]">
        <!-- Chat Header -->
        <div class="bg-blue-600 text-white px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('chat.index') }}" class="text-white hover:text-blue-200">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-xl font-bold">{{ $chatRoom->kos->nama_kos }}</h1>
                        <p class="text-blue-100 text-sm">
                            Chat dengan: {{ $otherUser->name }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <button onclick="refreshMessages()" class="text-white hover:text-blue-200 p-2">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                    <div class="dropdown relative">
                        <button class="text-white hover:text-blue-200 p-2" onclick="toggleDropdown()">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div id="dropdown-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-10">
                            <a href="{{ route('kos.show', $chatRoom->kos_id) }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                <i class="fas fa-eye mr-2"></i>Lihat Detail Kos
                            </a>
                            <button onclick="markAllAsRead()" class="w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100">
                                <i class="fas fa-check-double mr-2"></i>Tandai Semua Dibaca
                            </button>
                            <div class="border-t border-gray-200"></div>
                            <button onclick="confirmDeleteChat()" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50">
                                <i class="fas fa-trash mr-2"></i>Hapus Chat
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages Container -->
        <div class="flex-1 flex flex-col h-full">
            <!-- Messages Area -->
            <div class="flex-1 overflow-y-auto p-6 space-y-4" id="messages-container">
                <!-- Messages akan dimuat di sini -->
                <div class="flex items-center justify-center py-8">
                    <div class="text-center">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                        <p class="mt-2 text-gray-500 text-sm">Memuat pesan...</p>
                    </div>
                </div>
            </div>

            <!-- Message Input -->
            <div class="border-t bg-gray-50 p-4">
                <form id="message-form" class="flex space-x-4">
                    <div class="flex-1">
                        <textarea id="message-input" 
                                rows="1" 
                                placeholder="Ketik pesan Anda..." 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                maxlength="1000"></textarea>
                    </div>
                    <div class="flex flex-col space-y-2">
                        <button type="submit" 
                                id="send-button"
                                class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                        <div class="text-xs text-gray-500 text-center">
                            <span id="char-count">0</span>/1000
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Message Templates -->
<template id="message-template-sent">
    <div class="flex justify-end message-item">
        <div class="max-w-xs lg:max-w-md">
            <div class="bg-blue-600 text-white px-4 py-2 rounded-lg rounded-br-none">
                <p class="message-text">Pesan</p>
            </div>
            <div class="flex items-center justify-end mt-1 space-x-2">
                <span class="text-xs text-gray-500 message-time">10:30</span>
                <i class="fas fa-check text-xs text-gray-400 read-indicator hidden"></i>
                <i class="fas fa-check-double text-xs text-blue-500 read-indicator-double hidden"></i>
            </div>
        </div>
    </div>
</template>

<template id="message-template-received">
    <div class="flex justify-start message-item">
        <div class="max-w-xs lg:max-w-md">
            <div class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg rounded-bl-none">
                <p class="message-text">Pesan</p>
            </div>
            <div class="flex items-center justify-start mt-1">
                <span class="text-xs text-gray-500 message-time">10:30</span>
            </div>
        </div>
    </div>
</template>

@push('scripts')
<script>
const chatRoomId = {{ $chatRoom->id }};
const currentUserId = {{ auth()->id() }};
let lastMessageId = 0;

document.addEventListener('DOMContentLoaded', function() {
    loadMessages();
    setupMessageForm();
    setupAutoScroll();
    setupCharCounter();
    
    // Auto refresh setiap 5 detik
    setInterval(loadMessages, 5000);
});

function loadMessages() {
    fetch(`/api/chat/messages/${chatRoomId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderMessages(data.data.messages);
            }
        })
        .catch(error => console.error('Error loading messages:', error));
}

function renderMessages(messages) {
    const container = document.getElementById('messages-container');
    const shouldScroll = container.scrollTop + container.clientHeight >= container.scrollHeight - 100;
    
    // Clear container if this is initial load
    if (messages.length > 0 && lastMessageId === 0) {
        container.innerHTML = '';
    }
    
    messages.forEach(message => {
        if (message.id > lastMessageId) {
            const messageElement = createMessageElement(message);
            container.appendChild(messageElement);
            lastMessageId = Math.max(lastMessageId, message.id);
        }
    });
    
    if (shouldScroll) {
        scrollToBottom();
    }
}

function createMessageElement(message) {
    const isSent = message.sender_id === currentUserId;
    const template = document.getElementById(isSent ? 'message-template-sent' : 'message-template-received');
    const clone = template.content.cloneNode(true);
    
    // Set message text
    clone.querySelector('.message-text').textContent = message.message;
    
    // Set time
    const time = new Date(message.created_at).toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit'
    });
    clone.querySelector('.message-time').textContent = time;
    
    // Set read indicators for sent messages
    if (isSent) {
        if (message.is_read) {
            clone.querySelector('.read-indicator-double').classList.remove('hidden');
        } else {
            clone.querySelector('.read-indicator').classList.remove('hidden');
        }
    }
    
    return clone;
}

function setupMessageForm() {
    const form = document.getElementById('message-form');
    const input = document.getElementById('message-input');
    const sendButton = document.getElementById('send-button');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        sendMessage();
    });
    
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });
    
    // Auto-resize textarea
    input.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
    });
}

function sendMessage() {
    const input = document.getElementById('message-input');
    const message = input.value.trim();
    
    if (!message) return;
    
    const sendButton = document.getElementById('send-button');
    sendButton.disabled = true;
    
    fetch('/api/chat/send-message', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            chat_room_id: chatRoomId,
            message: message
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            input.value = '';
            input.style.height = 'auto';
            loadMessages();
            scrollToBottom();
        } else {
            alert('Gagal mengirim pesan: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengirim pesan');
    })
    .finally(() => {
        sendButton.disabled = false;
    });
}

function setupAutoScroll() {
    const container = document.getElementById('messages-container');
    // Auto scroll to bottom on initial load
    setTimeout(scrollToBottom, 500);
}

function setupCharCounter() {
    const input = document.getElementById('message-input');
    const counter = document.getElementById('char-count');
    
    input.addEventListener('input', function() {
        counter.textContent = this.value.length;
        
        if (this.value.length >= 950) {
            counter.classList.add('text-red-500');
        } else {
            counter.classList.remove('text-red-500');
        }
    });
}

function scrollToBottom() {
    const container = document.getElementById('messages-container');
    container.scrollTop = container.scrollHeight;
}

function refreshMessages() {
    loadMessages();
    showSuccess('Pesan berhasil diperbarui');
}

function markAllAsRead() {
    fetch(`/api/chat/mark-room-read/${chatRoomId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess('Semua pesan ditandai sudah dibaca');
            // Update read indicators
            document.querySelectorAll('.read-indicator').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.read-indicator-double').forEach(el => el.classList.remove('hidden'));
        }
    });
}

function confirmDeleteChat() {
    if (confirm('Apakah Anda yakin ingin menghapus chat ini? Semua pesan akan terhapus permanen.')) {
        deleteChat();
    }
}

function deleteChat() {
    fetch(`/api/chat/delete-room/${chatRoomId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess('Chat berhasil dihapus');
            window.location.href = '/chat';
        } else {
            alert('Gagal menghapus chat: ' + data.message);
        }
    });
}

function toggleDropdown() {
    const menu = document.getElementById('dropdown-menu');
    menu.classList.toggle('hidden');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    const dropdown = document.querySelector('.dropdown');
    const menu = document.getElementById('dropdown-menu');
    
    if (!dropdown.contains(e.target)) {
        menu.classList.add('hidden');
    }
});

function showSuccess(message) {
    // Simple toast notification
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}
</script>
@endpush
@endsection
