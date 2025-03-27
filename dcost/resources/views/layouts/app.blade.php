<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex flex-col">
            <!-- Navbar -->
            <nav class="bg-gradient-to-r from-indigo-500 to-purple-600 p-4 shadow-lg">
                <div class="max-w-7xl mx-auto flex justify-between items-center">
                    <a href="#" class="text-white text-2xl font-bold">Kostify</a>
                    <div class="space-x-4">
                        <a href="#" class="text-white hover:text-indigo-200">Profile</a>
                        <a href="#" class="text-white hover:text-indigo-200">Logout</a>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="flex-grow">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="bg-gray-800 text-white p-4 text-center">
                <p>© 2025 Kostify. Made for college vibes.</p>
            </footer>
        </div>
    </body>
</html>