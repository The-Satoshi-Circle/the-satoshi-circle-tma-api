<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NFTCollectionItem extends Model
{
    use HasFactory;

    protected $table = 'nft_collection_items';

    protected $fillable = [
        'name',
        'description',
        'image',
        'nft_collection_id',
    ];

    public function scopeNotAssigned(Builder $query): Builder
    {
        return $query->whereNull('user_id');
    }

    public function scopeNotMinted(Builder $query): Builder
    {
        return $query->where('minted', false);
    }
}
