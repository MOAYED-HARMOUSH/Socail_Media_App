<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'type',
        // 'type_type','type_id'
    ];

    public function location(): MorphTo
    {
        return $this->morphTo();
    }
}
