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
        'car_category_id',
        'prod_code',
        'item_name',
        'item_price',
        'item_cost',
        'item_info',
        'item_image',
    ];

    public function scopeSearchItems($query, $input = null)
        {
            if(!empty($input)){
                if(Item::where('item_name', 'like', '%'.$input . '%' )
                ->orWhere('item_info', 'like', '%'.$input . '%')->exists())
                {
                return $query->where('item_name', 'like', '%'.$input . '%' )
                ->orWhere('item_info', 'like', '%'.$input . '%');
                }
            }
        }

    public function itemCategory()
    {
        return $this->belongsTo(ItemCategory::class);
    }
}
