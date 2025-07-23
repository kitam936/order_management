<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Makes;



class CarCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_name',
        'makes_id',
        'car_info',
        'sort_order',
    ];

    public function makes()
    {
        return $this->belongsTo(Makes::class);
    }


}
