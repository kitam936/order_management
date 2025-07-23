<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Meeting extends Model
{
    /** @use HasFactory<\Database\Factories\MeetingFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'staff_id',
        'meeting_time',
        'memo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
