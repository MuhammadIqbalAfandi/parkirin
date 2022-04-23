<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mutation extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'amount',
        'transaction_id',
        'expense_id',
        'topup_id',
    ];
}
