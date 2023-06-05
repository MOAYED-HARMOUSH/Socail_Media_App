<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invite extends Pivot
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
