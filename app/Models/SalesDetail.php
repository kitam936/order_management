<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_id',
        'work_id',
        'item_id',
        'item_price',
        'item_pcs',
        'item_cost',
        'work_fee',
        'detail_info',
    ];

    public function sales()
    {
        return $this->belongsTo(Sales::class);
    }
}
