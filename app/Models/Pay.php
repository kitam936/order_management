<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pay extends Model
{
    protected $fillable = [
        'seikyu_id',
        'user_id',
        'pay_method',
        'paid_date',
        'paid_kingaku',

    ];
}
