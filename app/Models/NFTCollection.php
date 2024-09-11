<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Olifanton\Ton\Contracts\Nft\NftItem;

class NFTCollection extends Model
{
    use HasFactory;

    protected $table = 'nft_collections';

    protected $fillable = [
        'name',
        'description',
        'image'
    ];

    public function items(): HasMany
    {
        return $this->hasMany(NFTCollectionItem::class, 'nft_collection_id');
    }
}
