<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'dislikes_counts',
        'likes_counts',
        // 'location',
        'type',
        'content',
        'title',
        'reports_number'
    ];
}
