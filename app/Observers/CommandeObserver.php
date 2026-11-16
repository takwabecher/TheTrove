<?php

namespace App\Observers;

use App\Models\Commande;
use App\Services\GeocodingService;

class CommandeObserver
{
    public function __construct(
        private GeocodingService $geocoder
    ) {}

    public function saving(Commande $commande): void
    {
        if ($commande->isDirty('shipping_address') && (empty($commande->lat) || empty($commande->lng))) {
            $coords = $this->geocoder->geocode($commande->shipping_address);
            
            if ($coords) {
                $commande->lat = $coords['lat'];
                $commande->lng = $coords['lng'];
            }
        }
    }
}