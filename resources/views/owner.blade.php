@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-4 text-center">
            <h3 class="text-xl font-bold text-indigo-600">3</h3>
            <p class="text-gray-600">Properties Listed</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 text-center">
            <h3 class="text-xl font-bold text-purple-600">2</h3>
            <p class="text-gray-600">Active Bookings</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 text-center">
            <h3 class="text-xl font-bold text-green-600">Rp 5M</h3>
            <p class="text-gray-600">Earnings</p>
        </div>
    </div>

    <!-- Property Management -->
    <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-2xl font-semibold text-indigo-600 mb-4">Your Properties</h2>
        <div class="space-y-4">
            @for ($i = 0; $i < 2; $i++)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div>
                    <h3 class="text-lg font-semibold">Property #{{ $i + 1 }}</h3>
                    <p class="text-gray-600">[Location] - [Status]</p>
                </div>
                <button class="bg-indigo-500 text-white px-4 py-2 rounded-lg hover:bg-indigo-600">Edit</button>
            </div>
            @endfor
        </div>
        <button class="mt-4 bg-purple-500 text-white px-6 py-3 rounded-lg hover:bg-purple-600">Add New Property</button>
    </div>
</div>
@endsection