<?php

namespace App\Models;

use App\Services\CurrencyFormatService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'description',
        'price',
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

    protected function price(): Attribute
    {
        return Attribute::make(
            get:fn($value) => (new CurrencyFormatService)->setRupiahFormat($value, true)
        );
    }

    public function scopeFilter($query, $id)
    {
        $query->when($id ?? null, function ($query, $id) {
            $query->where('id', $id);
        });
    }
}
