<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeikyuStatus extends Model
{
    protected $fillable = [
        'id',
        'seikyu_status_name',
        'seikyu_status_info',
    ];
}
