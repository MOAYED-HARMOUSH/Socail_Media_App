<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Page extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'admin_id');
    }

    public function memberUsers(): BelongsToMany
    {
        return $this->belongsToMany(Community::class);
    }

    public function posts(): MorphMany
    {
        return $this->morphMany(Post::class,'location');
    }

    public function invites(): HasMany
    {
        return $this->hasMany(Invite::class);
    }
}
