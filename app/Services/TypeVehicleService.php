<?php

namespace App\Services;

use App\Models\TypeVehicle;

class TypeVehicleService
{
    public function isUsed(TypeVehicle $typeVehicle)
    {
        return $typeVehicle->vehicles()->exists() || $typeVehicle->maxVehicles()->exists() || $typeVehicle->outTransaction()->exists();
    }
}
