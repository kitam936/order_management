<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailStatus extends Model
{
    protected $fillable = [
        'id',
        'detail_status_name',
        'detail_status_info',
    ];
}
