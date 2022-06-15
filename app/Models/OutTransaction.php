<?php

namespace App\Models;

use App\Models\Mutation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'plat_number',
        'price',
        'entry_transaction_id',
        'type_vehicle_id',
        'user_id'
    ];

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get:fn($value) => Carbon::parse($value)->translatedFormat('l d/m/Y H:i:s')
        );
    }

    protected function platNumber(): Attribute
    {
        return Attribute::make(
            set:fn($value) => strtoupper($value)
        );
    }

    public function mutation()
    {
        return $this->hasOne(Mutation::class);
    }

    public function entryTransaction()
    {
        return $this->belongsTo(EntryTransaction::class, 'entry_transaction_id', 'transaction_number');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
