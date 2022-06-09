<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'exp_date',
        'type_member_id',
    ];

    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get:fn($value) => Carbon::parse($value)->translatedFormat('l d/m/Y')
        );
    }

    protected function expDate(): Attribute
    {
        return Attribute::make(
            get:fn($value) => Carbon::parse($value)->translatedFormat('l d/m/Y')
        );
    }

    public function typeMember()
    {
        return $this->belongsTo(TypeMember::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function topUps()
    {
        return $this->hasMany(TopUp::class);
    }

    public function vehicleDetail()
    {
        $detailVehicle = $this->vehicles->transform(fn($vehicle) => $vehicle->typeVehicle->type . ': ' . $vehicle->plat_number)->toArray();

        return implode(', ', $detailVehicle);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        });
    }
}
