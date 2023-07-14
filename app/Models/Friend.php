<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Friend extends Pivot
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function senders()
    {
        return $this->belongsTo(User::class, 'sender');
    }

    public function receivers()
    {
        return $this->belongsTo(User::class, 'receiver');
    }
}
