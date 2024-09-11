<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserSurvey extends Pivot
{
    use HasFactory;

    protected $table = 'users_surveys';

    protected $casts = [
        'data' => 'json'
    ];

    protected $fillable = [
        'data',
        'nft_collection_item_id'
    ];

    public function nftCollectionItem(): BelongsTo
    {
        return $this->belongsTo(NFTCollectionItem::class, 'nft_collection_item_id');
    }
}
