<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionOut extends Model
{
    use HasFactory;

    protected $fillable = [
        'plat_number',
        'price',
        'transaction_in_id',
        'type_vehicle_id',
        'user_id',
    ];
}
