<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    protected $fillable = [
        'id',
        'order_status_name',
        'order_status_info',
    ];
}
