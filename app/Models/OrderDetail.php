<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        // 'work_id',
        'item_id',
        'item_price',
        'item_pcs',
        'work_fee',
        'detail_status',
        'detail_info',
        'workingtime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
