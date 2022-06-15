<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntryTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'capture_vehicle',
        'transaction_number'
    ];

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get:fn($value) => Carbon::parse($value)->translatedFormat('l d/m/Y H:i:s')
        );
    }

    public function outTransaction()
    {
        return $this->hasOne(OutTransaction::class, 'entry_transaction_id', 'transaction_number');
    }
}
