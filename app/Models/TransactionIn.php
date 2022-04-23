<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionIn extends Model
{
    use HasFactory;

    protected $fillable = [
        'capture_vehicle',
        'transaction_number',
    ];
}
