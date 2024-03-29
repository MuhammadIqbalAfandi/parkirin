<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaxVehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'max',
        'type_member_id',
        'type_vehicle_id',
    ];

    public function typeVehicle()
    {
        return $this->belongsTo(TypeVehicle::class);
    }
}
