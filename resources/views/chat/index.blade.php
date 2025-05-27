@extends('layouts.app')
@section('title', 'Chat - Daftar Percakapan')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-blue-600 text-white px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Pesan</h1>
                    <p class="text-blue-100">Kelola percakapan Anda</p>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="bg-blue-500 px-3 py-1 rounded-full text-sm" id="unread-count">
                        Loading...
                    </span>
                    <button onclick="refreshChatList()" class="bg-blue-500 hover:bg-blue-400 px-4 py-2 rounded-lg transition">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
            </div>
        </div>

        <!-- Search & Filter -->
        <div class="px-6 py-4 border-b bg-gray-50">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" id="search-chat" placeholder="Cari percakapan..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="flex gap-2">
                    <select id="filter-status" class="px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">Semua Status</option>
                        <option value="unread">Belum Dibaca</option>
                        <option value="read">Sudah Dibaca</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Chat List -->
        <div class="divide-y divide-gray-200" id="chat-list">
            <!-- Chat items akan dimuat di sini -->
            <div class="flex items-center justify-center py-12">
                <div class="text-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
                    <p class="mt-4 text-gray-500">Memuat percakapan...</p>
                </div>
            </div>
        </div>

        <!-- Empty State -->
         <div class="text-center py-12" id="empty-state">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-comments text-6xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Percakapan</h3>
            <p class="text-gray-500 mb-6">Mulai chat dengan pemilik kos untuk melihat percakapan di sini</p>
            <a href="{{ route('kos.search') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                Cari Kos
            </a>
        </div>
    </div>
</div>

<!-- Chat Item Template -->
<template id="chat-item-template">
    <div class="chat-item p-6 hover:bg-gray-50 cursor-pointer transition-colors" data-chat-id="">
        <div class="flex items-center space-x-4">
            <!-- Avatar -->
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                    <span class="avatar-text">A</span>
                </div>
            </div>
            
            <!-- Chat Info -->
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-semibold text-gray-900 truncate chat-name">Nama Kos</p>
                        <p class="text-sm text-gray-500 truncate other-user">Dengan: Nama User</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500 chat-time">2 jam yang lalu</p>
                        <div class="unread-badge hidden bg-red-500 text-white text-xs px-2 py-1 rounded-full mt-1">
                            <span class="unread-count">0</span>
                        </div>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mt-1 truncate last-message">Pesan terakhir...</p>
            </div>
            
            <!-- Status Indicator -->
            <div class="flex-shrink-0">
                <div class="w-3 h-3 bg-green-400 rounded-full status-indicator"></div>
            </div>
        </div>
    </div>
</template>

<!-- Tambahkan container untuk daftar chat -->
<div id="chat-list"></div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadChatList();
    loadUnreadCount();
    setupSearchAndFilter();
    
    // Auto refresh setiap 30 detik
    setInterval(function() {
        loadChatList();
        loadUnreadCount();
    }, 30000);
});

function loadChatList( {
    loadDummyChats();
})
function loadUnreadCount() {
    fetch('/api/chat/notification-count')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const unreadCount = data.data.unread_count;
                document.getElementById('unread-count').textContent = 
                    unreadCount > 0 ? `${unreadCount} belum dibaca` : 'Semua terbaca';
            }
        })
        .catch(error => console.error('Error loading unread count:', error));
}

function renderChatList(chatRooms) {
    const chatList = document.getElementById('chat-list');
    const emptyState = document.getElementById('empty-state');
    const template = document.getElementById('chat-item-template');
    
    // Tambahkan cek Array
    if (!Array.isArray(chatRooms) || chatRooms.length === 0) {
    // Kosong, tampilkan empty state
    document.getElementById('chat-list').innerHTML = '';
    document.getElementById('empty-state').classList.remove('hidden');  // Tampilkan
    return;
}

    // Jika ada data, sembunyikan empty state
    document.getElementById('empty-state').classList.add('hidden');
    
    chatRooms.forEach(chatRoom => {
        const clone = template.content.cloneNode(true);
        const chatItem = clone.querySelector('.chat-item');
        
        chatItem.dataset.chatId = chatRoom.id;
        
        const avatarText = chatRoom.kos.nama_kos.charAt(0).toUpperCase();
        clone.querySelector('.avatar-text').textContent = avatarText;
        
        clone.querySelector('.chat-name').textContent = chatRoom.kos.nama_kos;
        
        const currentUserId = {{ auth()->id() }};
        const otherUser = chatRoom.tenant_id === currentUserId ? chatRoom.owner : chatRoom.tenant;
        clone.querySelector('.other-user').textContent = `Dengan: ${otherUser.name}`;
        
        const lastMessage = chatRoom.messages && chatRoom.messages.length > 0 
            ? chatRoom.messages[0].message 
            : 'Belum ada pesan';
        clone.querySelector('.last-message').textContent = lastMessage;
        
        const timeAgo = new Date(chatRoom.updated_at).toLocaleString('id-ID');
        clone.querySelector('.chat-time').textContent = timeAgo;
        
        if (chatRoom.unread_count > 0) {
            const unreadBadge = clone.querySelector('.unread-badge');
            unreadBadge.classList.remove('hidden');
            clone.querySelector('.unread-count').textContent = chatRoom.unread_count;
        }
        
        chatItem.addEventListener('click', function() {
            window.location.href = `/chat/room/${chatRoom.id}`;
        });
        
        chatList.appendChild(clone);
    });
}

/ Contoh data dummy chat rooms
    const dummyChatRooms = [
        {
            id: 1,
            kos: { nama_kos: "Kos Mawar" },
            tenant_id: 2,
            owner: { name: "Budi" },
            tenant: { name: "Andi" },
            messages: [{ message: "Halo, ada kamar kosong?" }],
            updated_at: new Date(Date.now() - 3600 * 1000).toISOString(), // 1 jam lalu
            unread_count: 2,
        },
        {
            id: 2,
            kos: { nama_kos: "Kos Melati" },
            tenant_id: 3,
            owner: { name: "Sari" },
            tenant: { name: "Joko" },
            messages: [{ message: "Saya tertarik dengan kamar yang Anda tawarkan." }],
            updated_at: new Date(Date.now() - 7200 * 1000).toISOString(), // 2 jam lalu
            unread_count: 0,
        },
    ];

    // Fungsi untuk memunculkan dummy chat (panggil ini untuk testing)
    function loadDummyChats() {
        renderChatList(dummyChatRooms);
        document.getElementById('empty-state').classList.add('hidden'); // sembunyikan empty state
        document.getElementById('unread-count').textContent = '2 belum dibaca'; // contoh update unread count
    }

    // Panggil fungsi loadDummyChats saat halaman siap, jika mau langsung tampil
    document.addEventListener('DOMContentLoaded', function() {
        loadDummyChats(); // uncomment ini jika ingin langsung tampilkan dummy chat
    });

function setupSearchAndFilter() {
    const searchInput = document.getElementById('search-chat');
    const filterStatus = document.getElementById('filter-status');
    
    searchInput.addEventListener('input', filterChats);
    filterStatus.addEventListener('change', filterChats);
}

function filterChats() {
    const searchTerm = document.getElementById('search-chat').value.toLowerCase();
    const statusFilter = document.getElementById('filter-status').value;
    const chatItems = document.querySelectorAll('.chat-item');
    
    chatItems.forEach(item => {
        const chatName = item.querySelector('.chat-name').textContent.toLowerCase();
        const otherUser = item.querySelector('.other-user').textContent.toLowerCase();
        const hasUnread = !item.querySelector('.unread-badge').classList.contains('hidden');
        
        let matchesSearch = chatName.includes(searchTerm) || otherUser.includes(searchTerm);
        let matchesStatus = true;
        
        if (statusFilter === 'unread') {
            matchesStatus = hasUnread;
        } else if (statusFilter === 'read') {
            matchesStatus = !hasUnread;
        }
        
        if (matchesSearch && matchesStatus) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

function refreshChatList() {
    loadChatList();
    loadUnreadCount();
    showSuccess('Daftar chat berhasil diperbarui');
}

function showError(message) {
    // Implement toast notification
    console.error(message);
}

function showSuccess(message) {
    // Implement toast notification
    console.log(message);
}

</script>
@endpush
@endsection