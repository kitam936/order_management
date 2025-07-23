<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CarCategory;

class Makes extends Model
{
    use HasFactory;

    protected $fillable = [
        'makes_name',
        'makes_info',
        'sort_order',
    ];

    public function carCategories()
    {
        return $this->hasMany(CarCategory::class);
    }
}
