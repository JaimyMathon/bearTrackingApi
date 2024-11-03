<?php

namespace App\Http\Filters\V1;

use App\Models\Bear;
use Illuminate\Database\Eloquent\Collection;

class BearFilter
{
    public function findBearsNearby(float $latitude, float $longitude, float $radius = 25): Collection
    {
        $conversionFactor = 1.3; // Omrekenfactor voor wegafstand
        $maxDirectDistance = $radius / $conversionFactor; // Maximale rechte afstand die overeenkomt met een straal van 25 km over de weg

        return Bear::selectRaw("
                id, name, city, region, latitude, longitude,
                (6371 * acos(cos(radians(?)) * cos(radians(latitude)) 
                * cos(radians(longitude) - radians(?)) 
                + sin(radians(?)) * sin(radians(latitude)))) AS distance
            ", [$latitude, $longitude, $latitude])
            ->having("distance", "<=", $maxDirectDistance)
            ->orderBy("distance")
            ->get();
    }
}
