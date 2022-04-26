<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'plat_number',
        'member_id',
        'type_vehicle_id',
    ];

    protected function platNumber(): Attribute
    {
        return Attribute::make(
            set:fn($value) => strtoupper($value)
        );
    }

    public function typeVehicle()
    {
        return $this->belongsTo(TypeVehicle::class);
    }
}
