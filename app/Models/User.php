<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * @property integer $id
 * @property integer $telegram_id
 * @property string $first_name
 * @property string $last_name
 * @property string $username
 * @property string $language_code
 * @property boolean $is_premium
 * @property boolean $allows_write_to_pm
 * @property integer $coins
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'telegram_id',
        'first_name',
        'last_name',
        'username',
        'language_code',
        'is_premium',
        'allows_write_to_pm',
    ];

    public $appends = [
        'daily_taps',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'users_tasks');
    }

    public function getDailyTapsAttribute(): int
    {
        return $this->transactions()
                    ->today()
                    ->ofType(Transaction::TYPE_DAILY_TAPS)
                    ->sum('amount');
    }

    public function telegramValidToken(): PersonalAccessToken|null
    {
        return $this->tokens()
                    ->where('name', 'telegram')
                    ->where(function (Builder $builder) {
                        return $builder->whereDate('expires_at', '>', now())->orWhereNull('expires_at');
                    })
                    ->first();
    }
}
