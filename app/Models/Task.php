<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public const TYPE_LINK = 1;
    public const TYPE_GROUP = 2;

    protected $table = 'tasks';

    protected $fillable = [
        'name',
        'description',
        'type',
        'url'
    ];
}
