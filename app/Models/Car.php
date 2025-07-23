<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Makes;

class Car extends Model
{
    /** @use HasFactory<\Database\Factories\CarFactory> */
    use HasFactory;

    protected $fillable = [
        'car_category_id',
        'user_id',
        'regist_date',
        'inspect_date',
        'car_info',
        'car_image',
        'sort_order',
    ];

    public function carCategory()
    {
        return $this->belongsTo(CarCategory::class);
    }
}
