<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class LocationHelper
{
    public static function geocodeAddress($alamat)
    {
        $response = Http::get('https://nominatim.openstreetmap.org/search', [
            'q' => $alamat,
            'format' => 'json',
            'limit' => 1,
        ]);

        if ($response->successful() && count($response->json()) > 0) {
            $data = $response->json()[0];
            return [
                'latitude' => $data['lat'],
                'longitude' => $data['lon'],
            ];
        }

        return null;
    }
}