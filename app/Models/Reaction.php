<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        // 'location_type','location_id',
        'user_id',
        // 'location',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function location(): MorphTo
    {
        return $this->morphTo();
    }
}
