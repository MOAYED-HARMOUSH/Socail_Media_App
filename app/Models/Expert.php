<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expert extends Model
{
    use HasFactory;

    protected $fillable = [
        'companies',
        'years_as_expert',
        'work_at_company',
        'start_year',
        'section',
    ];
}
