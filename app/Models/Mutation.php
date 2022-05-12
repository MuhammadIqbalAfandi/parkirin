<?php

namespace App\Models;

use App\Services\CurrencyFormatService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mutation extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'amount',
        'transaction_out_id',
        'expense_id',
        'topup_id',
    ];

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get:fn($value) => Carbon::parse($value)->translatedFormat('l d/m/Y')
        );
    }

    protected function amount(): Attribute
    {
        return Attribute::make(
            get:function ($value) {
                if ($this->getRawOriginal('type') == 1) {
                    return (new CurrencyFormatService)->setRupiahFormat($value, true);
                } else {
                    return (new CurrencyFormatService)->setRupiahFormat(-$value, true);
                }
            },
        );
    }

    protected function type(): Attribute
    {
        return Attribute::make(
            get:fn($value) => $value == 1 ? __('words.income') : __('words.expense')
        );
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['startDate'] ?? null, function ($query, $date) {
            $query->whereDate('created_at', '>=', $date);
        })->when($filters['endDate'] ?? null, function ($query, $date) {
            $query->whereDate('created_at', '<=', $date);
        })->when($filters['outlet'] ?? null, function ($query, $outlet) {
            $query->where('outlet_id', $outlet);
        });
    }
}
