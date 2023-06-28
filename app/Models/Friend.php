<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Friend extends Pivot
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];
    public function User_sender():BelongsTo
    {

        return $this->belongsTo(User::class, 'sender',);

    }
    public function User_reciever():BelongsTo
    {

        return $this->belongsTo(User::class, 'reciever',);

    }
}
