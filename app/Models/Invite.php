<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invite extends Pivot
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    protected $table = 'invites';

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver');
    }
}
