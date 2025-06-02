<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RouteHelper
{
    public static function getRoute($startLat, $startLon, $endLat, $endLon, $mode = 'driving-car')
    {
        $apiKey = "5b3ce3597851110001cf6248e5faaff23da64d0e982a73d2de61c6e4";

        $url = 'https://api.openrouteservice.org/v2/directions/' . $mode;

        try {
            $response = Http::withHeaders([
                'Authorization' => $apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->post($url, [
                'coordinates' => [
                    [(float) $startLon, (float) $startLat],
                    [(float) $endLon, (float) $endLat]
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'distance' => $data['features'][0]['properties']['summary']['distance'], // dalam meter
                    'duration' => $data['features'][0]['properties']['summary']['duration'], // dalam detik
                    'geometry' => $data['features'][0]['geometry'] // polyline (opsional untuk digambar)
                ];
            } else {
                Log::error('ORS error: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('ORS exception: ' . $e->getMessage());
        }

        return null;
    }
}
