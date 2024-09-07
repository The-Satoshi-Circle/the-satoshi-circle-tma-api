<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserSurvey extends Pivot
{
    use HasFactory;

    protected $casts = [
        'data' => 'json'
    ];
}
