<?php

use App\Models\User_profile;
use Illuminate\Support\Facades\Auth;

if (!function_exists('user_profile')) {
    function user_profile()
    {
        $userId = session('user_id'); // Sesuaikan dengan cara Anda menyimpan ID pengguna
        return $userId ? User_profile::where('user_id', $userId)->first() : null;
    }
}
