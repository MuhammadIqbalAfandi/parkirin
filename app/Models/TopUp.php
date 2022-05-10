<?php

namespace App\Models;

use App\Services\CurrencyFormatService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopUp extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'exp_date',
        'member_id',
        'user_id',
    ];

    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get:fn($value) => Carbon::parse($value)->translatedFormat('l d/m/Y')
        );
    }

    protected function amount(): Attribute
    {
        return Attribute::make(
            get:fn($value) => (new CurrencyFormatService)->setRupiahFormat($value, true)
        );
    }

    protected function expDate(): Attribute
    {
        return Attribute::make(
            get:fn($value) => Carbon::parse($value)->translatedFormat('l d/m/Y')
        );
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mutation()
    {
        return $this->hasOne(Mutation::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->orWhereHas('member', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%');
                });
            });
        })->when($filters['startDate'] ?? null, function ($query, $date) {
            $query->whereDate('created_at', '>=', $date);
        })->when($filters['endDate'] ?? null, function ($query, $date) {
            $query->whereDate('created_at', '<=', $date);
        });
    }
}
