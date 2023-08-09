<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Community extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;

    protected $guarded = [
        'id'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps()
            ->as('subscription');
    }

    public function posts(): MorphMany
    {
        return $this->morphMany(Post::class,'location');
    }
}
