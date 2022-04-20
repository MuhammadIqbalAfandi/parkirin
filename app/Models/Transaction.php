<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'login_time',
        'time_out',
        'price',
        'plat_number',
        'input_by',
        'capture_vehicle',
        'type_vehicle_id',
        'user_id',
        'member_id',
    ];
}
