<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nyukin extends Model
{
    /** @use HasFactory<\Database\Factories\NyukinFactory> */
    use HasFactory;

    protected $fillable = [
        'sales_id',
        'shop_id',
        'user_id',
        'pay_method',
        'nyukin_date',
        'nyukin_kingaku',

    ];
}
