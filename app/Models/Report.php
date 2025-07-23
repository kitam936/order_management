<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;

class Report extends Model
{
    /** @use HasFactory<\Database\Factories\ReportFactory> */
    use HasFactory;

    protected $fillable = [
        'id',
        'detail_id',
        'staff_id',
        'title',
        'report',
        'image1',
        'image2',
        'image3',
        'image4',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

}
