// File: resources/views/dashboard/owner.blade.php (dashboard untuk pemilik kos)
@extends('layouts.app')

@section('title', 'Dashboard Pemilik Kos')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg text-white p-8 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">Selamat Datang, {{ auth()->user()->name }}!</h1>
                <p class="text-blue-100">Kelola kos dan chat dengan calon penyewa dengan mudah</p>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-home text-6xl text-blue-200"></i>
            </div>
        </div>
    </div>

    <!-- Chat Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    <i class="fas fa-comments text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Chat</p>
                    <p class="text-2xl font-bold text-gray-900" id="total-chats">-</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100">
                    <i class="fas fa-clock text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Chat Aktif Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900" id="active-chats">-</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100">
                    <i class="fas fa-envelope text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pesan Belum Dibaca</p>
                    <p class="text-2xl font-bold text-gray-900" id="unread-messages">-</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100">
                    <i class="fas fa-building text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Kos</p>
                    <p class="text-2xl font-bold text-gray-900" id="total-kos">-</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Aksi Cepat</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('chat.index') }}" class="bg-blue-600 text-white p-4 rounded-lg hover:bg-blue-700 transition text-center">
                <i class="fas fa-comments text-2xl mb-2"></i>
                <p class="font-semibold">Lihat Semua Chat</p>
                <p class="text-sm text-blue-100">Kelola percakapan dengan penyewa</p>
            </a>
            
            <a href="{{ route('kos.create') }}" class="bg-green-600 text-white p-4 rounded-lg hover:bg-green-700 transition text-center">
                <i class="fas fa-plus-circle text-2xl mb-2"></i>
                <p class="font-semibold">Tambah Kos Baru</p>
                <p class="text-sm text-green-100">Daftarkan kos baru Anda</p>
            </a>
            
            <a href="{{ route('kos.manage') }}" class="bg-purple-600 text-white p-4 rounded-lg hover:bg-purple-700 transition text-center">
                <i class="fas fa-cog text-2xl mb-2"></i>
                <p class="font-semibold">Kelola Kos</p>
                <p class="text-sm text-purple-100">Edit dan atur kos Anda</p>
            </a>
        </div>
    </div>

    <!-- Recent Chat Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Messages -->
        <div class="bg-white rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold">Pesan Terbaru</h2>
                    <a href="{{ route('chat.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                        Lihat Semua
                    </a>
                </div>
            </div>
            <div class="p-6">
                <div id="recent-messages">
                    <!-- Loading state -->
                    <div class="flex items-center justify-center py-8">
                        <div class="text-center">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                            <p class="mt-2 text-gray-500 text-sm">Memuat pesan...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Popular Kos -->
        <div class="bg-white rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold">Kos Paling Diminati</h2>
                    <a href="{{ route('kos.manage') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                        Kelola Semua
                    </a>
                </div>
            </div>
            <div class="p-6">
                <div id="popular-kos">
                    <!-- Loading state -->
                    <div class="flex items-center justify-center py-8">
                        <div class="text-center">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                            <p class="mt-2 text-gray-500 text-sm">Memuat data...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Chart -->
    <div class="bg-white rounded-lg shadow-lg mt-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold">Aktivitas Chat Mingguan</h2>
        </div>
        <div class="p-6">
            <canvas id="chatActivityChart" width="400" height="200"></canvas>
        </div>
    </div>
</div>

<!-- Recent Message Template -->
<template id="recent-message-template">
    <div class="flex items-center space-x-4 py-3 border-b border-gray-100 last:border-b-0">
        <div class="flex-shrink-0">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                <span class="avatar-text">A</span>
            </div>
        </div>
        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between">
                <p class="font-medium text-gray-900 truncate sender-name">Nama Pengirim</p>
                <p class="text-xs text-gray-500 message-time">2 jam lalu</p>
            </div>
            <p class="text-sm text-gray-600 truncate kos-name">Nama Kos</p>
            <p class="text-sm text-gray-500 truncate message-preview">Preview pesan...</p>
        </div>
        <div class="flex-shrink-0">
            <div class="unread-indicator hidden w-3 h-3 bg-red-500 rounded-full"></div>
        </div>
    </div>
</template>

<!-- Popular Kos Template -->
<template id="popular-kos-template">
    <div class="flex items-center space-x-4 py-3 border-b border-gray-100 last:border-b-0">
        <div class="flex-shrink-0">
            <img class="w-12 h-12 rounded-lg object-cover kos-image" src="" alt="Kos Image">
        </div>
        <div class="flex-1 min-w-0">
            <p class="font-medium text-gray-900 truncate kos-name">Nama Kos</p>
            <p class="text-sm text-gray-500 kos-price">Rp 0/bulan</p>
            <div class="flex items-center mt-1">
                <i class="fas fa-comments text-xs text-blue-500 mr-1"></i>
                <span class="text-xs text-gray-500 chat-count">0 chat</span>
                <i class="fas fa-eye text-xs text-green-500 ml-3 mr-1"></i>
                <span class="text-xs text-gray-500 view-count">0 views</span>
            </div>
        </div>
        <div class="flex-shrink-0">
            <a href="#" class="text-blue-600 hover:text-blue-800 text-sm kos-link">Lihat</a>
        </div>
    </div>
</template>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadDashboardStats();
    loadRecentMessages();
    loadPopularKos();
    loadChatActivityChart();
    
    // Auto refresh setiap 5 menit
    setInterval(function() {
        loadDashboardStats();
        loadRecentMessages();
    }, 300000);
});

function loadDashboardStats() {
    fetch('/api/dashboard/owner-stats')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const stats = data.data;
                document.getElementById('total-chats').textContent = stats.total_chats || 0;
                document.getElementById('active-chats').textContent = stats.active_chats || 0;
                document.getElementById('unread-messages').textContent = stats.unread_messages || 0;
                document.getElementById('total-kos').textContent = stats.total_kos || 0;
            }
        })
        .catch(error => {
            console.error('Error loading dashboard stats:', error);
            // Set fallback values
            document.getElementById('total-chats').textContent = '0';
            document.getElementById('active-chats').textContent = '0';
            document.getElementById('unread-messages').textContent = '0';
            document.getElementById('total-kos').textContent = '0';
        });
}

function loadRecentMessages() {
    fetch('/api/dashboard/recent-messages')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderRecentMessages(data.data);
            }
        })
        .catch(error => {
            console.error('Error loading recent messages:', error);
            document.getElementById('recent-messages').innerHTML = 
                '<p class="text-center text-gray-500 py-8">Gagal memuat pesan terbaru</p>';
        });
}

function renderRecentMessages(messages) {
    const container = document.getElementById('recent-messages');
    const template = document.getElementById('recent-message-template');
    
    if (messages.length === 0) {
        container.innerHTML = '<p class="text-center text-gray-500 py-8">Belum ada pesan</p>';
        return;
    }
    
    container.innerHTML = '';
    
    messages.forEach(message => {
        const clone = template.content.cloneNode(true);
        
        // Set avatar
        const avatarText = message.sender.name.charAt(0).toUpperCase();
        clone.querySelector('.avatar-text').textContent = avatarText;
        
        // Set content
        clone.querySelector('.sender-name').textContent = message.sender.name;
        clone.querySelector('.kos-name').textContent = message.chat_room.kos.nama_kos;
        clone.querySelector('.message-preview').textContent = message.message;
        
        // Set time
        const timeAgo = formatTimeAgo(new Date(message.created_at));
        clone.querySelector('.message-time').textContent = timeAgo;
        
        // Show unread indicator if not read
        if (!message.is_read) {
            clone.querySelector('.unread-indicator').classList.remove('hidden');
        }
        
        // Add click handler to go to chat room
        const messageItem = clone.querySelector('.flex');
        messageItem.classList.add('cursor-pointer', 'hover:bg-gray-50');
        messageItem.addEventListener('click', function() {
            window.location.href = `/chat/room/${message.chat_room_id}`;
        });
        
        container.appendChild(clone);
    });
}

function loadPopularKos() {
    fetch('/api/dashboard/popular-kos')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderPopularKos(data.data);
            }
        })
        .catch(error => {
            console.error('Error loading popular kos:', error);
            document.getElementById('popular-kos').innerHTML = 
                '<p class="text-center text-gray-500 py-8">Gagal memuat data kos</p>';
        });
}

function renderPopularKos(kosList) {
    const container = document.getElementById('popular-kos');
    const template = document.getElementById('popular-kos-template');
    
    if (kosList.length === 0) {
        container.innerHTML = '<p class="text-center text-gray-500 py-8">Belum ada data kos</p>';
        return;
    }
    
    container.innerHTML = '';
    
    kosList.forEach(kos => {
        const clone = template.content.cloneNode(true);
        
        // Set image
        const defaultImage = '/images/default-kos.jpg';
        const imageUrl = kos.images && kos.images.length > 0 ? kos.images[0].url : defaultImage;
        clone.querySelector('.kos-image').src = imageUrl;
        
        // Set content
        clone.querySelector('.kos-name').textContent = kos.nama_kos;
        clone.querySelector('.kos-price').textContent = `Rp ${formatCurrency(kos.harga)}/bulan`;
        clone.querySelector('.chat-count').textContent = `${kos.chat_count || 0} chat`;
        clone.querySelector('.view-count').textContent = `${kos.view_count || 0} views`;
        
        // Set link
        clone.querySelector('.kos-link').href = `/kos/${kos.id}`;
        
        container.appendChild(clone);
    });
}

function loadChatActivityChart() {
    fetch('/api/dashboard/chat-activity')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderChatActivityChart(data.data);
            }
        })
        .catch(error => {
            console.error('Error loading chat activity:', error);
        });
}

function renderChatActivityChart(activityData) {
    const ctx = document.getElementById('chatActivityChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: activityData.labels,
            datasets: [
                {
                    label: 'Pesan Masuk',
                    data: activityData.incoming_messages,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Chat Baru',
                    data: activityData.new_chats,
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            }
        }
    });
}

function formatTimeAgo(date) {
    const now = new Date();
    const diffInMs = now - date;
    const diffInMinutes = Math.floor(diffInMs / 60000);
    const diffInHours = Math.floor(diffInMs / 3600000);
    const diffInDays = Math.floor(diffInMs / 86400000);
    
    if (diffInMinutes < 1) {
        return 'Baru saja';
    } else if (diffInMinutes < 60) {
        return `${diffInMinutes} menit lalu`;
    } else if (diffInHours < 24) {
        return `${diffInHours} jam lalu`;
    } else if (diffInDays < 7) {
        return `${diffInDays} hari lalu`;
    } else {
        return date.toLocaleDateString('id-ID');
    }
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID').format(amount);
}
</script>
@endpush
@endsection