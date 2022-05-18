<?php

namespace App\Models;

use App\Models\MaxVehicle;
use App\Models\OutTransaction;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeVehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
    ];

    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get:fn($value) => Carbon::parse($value)->translatedFormat('l d/m/Y')
        );
    }

    protected function type(): Attribute
    {
        return Attribute::make(
            set:fn($value) => ucwords($value)
        );
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function maxVehicles()
    {
        return $this->hasMany(MaxVehicle::class);
    }

    public function outTransaction()
    {
        return $this->hasOne(OutTransaction::class);
    }
}
