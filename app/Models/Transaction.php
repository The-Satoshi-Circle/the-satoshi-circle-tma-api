<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'amount',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public const TYPES = [
        self::TYPE_DAILY_TAPS,
        self::TYPE_TASK
    ];

    public const TYPE_DAILY_TAPS = 1;

    public const TYPE_TASK = 2;

    public const TYPE_SURVEY = 3;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeToday(Builder $query): Builder
    {
        return $query->whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()]);
    }

    public function scopeOfType(Builder $query, int $type): Builder
    {
        return $query->where('type', $type);
    }
}
