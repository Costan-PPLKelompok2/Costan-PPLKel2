<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md hidden md:block">
        <div class="p-6 font-bold text-lg">
            Menu
        </div>
        <nav class="px-4">
            <ul>
                <li class="mb-4">
                    <a href="{{ route('profile.show') }}" class="text-gray-700 hover:text-blue-500">Lihat Profil</a>
                </li>
                <li class="mb-4">
                    <a href="{{ route('profile.edit') }}" class="text-gray-700 hover:text-blue-500">Edit Profil</a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
        <!-- Navbar -->
        <header class="bg-white shadow-md p-4 flex justify-between items-center">
            <div class="text-lg font-bold">Dashboard</div>
            <div class="flex items-center gap-4">
                <span class="text-gray-700">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-red-500 hover:underline">Logout</button>
                </form>
            </div>
        </header>

        <!-- Content -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

</body>
</html>
