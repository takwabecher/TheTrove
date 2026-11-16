<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeocodingService
{
    /**
     * Convert a Tunisian address to GPS coordinates [lat, lng].
     * Returns null if the address cannot be geocoded.
     */
    public function geocode(string $address): ?array
    {
        // Append "Tunisia" to improve search accuracy
        $query = trim($address) . ', Tunisia';

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'TheTroveDelivery/1.0 (support@thetrove.tn)' // Required by Nominatim
            ])->timeout(10)->get('https://nominatim.openstreetmap.org/search', [
                'q'            => $query,
                'format'       => 'json',
                'limit'        => 1,
                'countrycodes' => 'tn', // Restrict to Tunisia only
            ]);

            $data = $response->json();

            if (!empty($data[0])) {
                return [
                    'lat' => (float) $data[0]['lat'],
                    'lng' => (float) $data[0]['lon'],
                ];
            }
        } catch (\Exception $e) {
            Log::warning('Geocoding failed', [
                'address' => $address,
                'error'   => $e->getMessage(),
            ]);
        }

        return null;
    }
}