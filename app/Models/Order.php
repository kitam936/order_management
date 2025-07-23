<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App_models\OrderDetail;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'car_id',
        'shop_id',
        'staff_id',
        'order_status',
        'order_info',
        'pitin_date',
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
