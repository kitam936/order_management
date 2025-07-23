<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ItemCategory;

class Item extends Model
{
    /** @use HasFactory<\Database\Factories\ItemFactory> */
    use HasFactory;

    protected $fillable = [
        'item_category_id',
        'prod_code',
        'item_name',
        'item_price',
        'item_cost',
        'item_info',
        'item_image',
    ];

    public function itemCategory()
    {
        return $this->belongsTo(ItemCategory::class);
    }
}
