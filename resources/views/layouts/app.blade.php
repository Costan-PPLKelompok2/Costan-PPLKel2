<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @livewireStyles
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Aplikasi Kos') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Vite Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Livewire Styles -->
    @livewireStyles
    <!-- Leaflet CSS -->
    <link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    />
</head>
<body class="font-sans antialiased">
    <!-- Banner -->
    <x-banner />

    <!-- Main App Container -->
    <div class="min-h-screen bg-gray-100">
        @livewire('navigation-menu')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="container py-4">
            @yield('content')
            {{ $slot ?? '' }}
        </main>
    </div>

    <!-- Modals & Scripts -->
    @stack('modals')
    @livewireScripts

    <script>
        function updateChatNotificationBadge() {
            // Pastikan URL fetch ini benar, jika Anda menggunakan routes/api.php, mungkin menjadi '/api/chat/notification-count'
            fetch("{{ route('api.chat.notificationCount') }}") // Menggunakan named route API
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.data && typeof data.data.unread_count !== 'undefined') {
                        const badge = document.getElementById('chat-notification-badge');
                        const count = data.data.unread_count;

                        if (badge) { // Pastikan elemen badge ada
                            if (count > 0) {
                                badge.textContent = count > 99 ? '99+' : count;
                                badge.classList.remove('d-none');
                            } else {
                                badge.classList.add('d-none');
                            }
                        }
                    } else if (data.error) {
                        console.error('Error fetching notification count:', data.error);
                    }
                })
                .catch(error => console.error('Error updating notification badge:', error));
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Panggil sekali saat halaman dimuat jika pengguna sudah login
            @auth
                updateChatNotificationBadge();
                // Update setiap 30 detik
                setInterval(updateChatNotificationBadge, 30000); // 30000 ms = 30 detik
            @endauth
        });
    </script>
    @stack('scripts')

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" defer></script>
</body>
</html>