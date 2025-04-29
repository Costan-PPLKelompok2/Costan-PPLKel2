@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <!-- Search Bar -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <div class="flex items-center space-x-4">
            <input type="text" placeholder="Search for a kost (e.g., near campus)" class="w-full p-3 rounded-lg border focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <button class="bg-indigo-500 text-white px-6 py-3 rounded-lg hover:bg-indigo-600">Search</button>
        </div>
    </div>

    <!-- Listings Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @for ($i = 0; $i < 3; $i++)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
            <div class="h-48 bg-gray-300"></div> <!-- Placeholder Image -->
            <div class="p-4">
                <h3 class="text-lg font-semibold text-indigo-600">Kost Name #{{ $i + 1 }}</h3>
                <p class="text-gray-600">Near [Campus Name], Rp [Price]/month</p>
                <button class="mt-2 bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-600">Book Now</button>
            </div>
        </div>
        @endfor
    </div>
</div>
@endsection