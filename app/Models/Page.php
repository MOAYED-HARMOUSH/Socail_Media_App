<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'image_path',
        'cover_image_path',
        'bio',
        'follower_counts',
        'email',
        'admin_id'
    ];
}
