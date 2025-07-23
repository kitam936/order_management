<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item;

class ItemCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'item_category_name',
        'item_category_info',
        'sort_order',
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
