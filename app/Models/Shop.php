<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Company;
use App\Models\Sale;
use App\Models\Delivery;
use App\Models\Area;
use App\Models\Stock;
use App\Models\Report;
use App\Models\User;



class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'shop_name',
        'company_id',
        'shop_info',
        'shop_postcode',
        'shop_address',
        'shop_tel',
        'rate',
        'is_selling'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function sale()
    {
        return $this->hasMany(Sale::class);
    }

    public function stock()
    {
        return $this->hasMany(Stock::class);
    }

    public function report()
    {
        return $this->hasMany(Report::class);
    }




}
