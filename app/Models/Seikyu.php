<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seikyu extends Model
{
    protected $fillable = [
        'seikyu_date',
        'shop_id',
        'user_id',
        'car_id',
        'staff_id',
        'order_id',
        'seikyu_kingaku',
        'seikyu_info',
        'seikyu_status',

    ];
}
