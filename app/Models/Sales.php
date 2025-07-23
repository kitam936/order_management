<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SalesDetail;

class Sales extends Model
{
    /** @use HasFactory<\Database\Factories\SalesFactory> */
    use HasFactory;

    protected $fillable = [
        'sales_date',
        'shop_id',
        'user_id',
        'car_id',
        'staff_id',
        'order_id',
        'order_kingaku',
        'adjust',
        'total_kingaku',
        'seikyu_info',
        'nyukin_limit',
        'seikyu_status',

    ];

    public function salesDetails()
    {
        return $this->hasMany(SalesDetail::class);
    }

}
