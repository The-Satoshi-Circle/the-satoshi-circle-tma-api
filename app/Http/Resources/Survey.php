<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Survey extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $done = $request->user()->surveys()->where('survey_id', $this->id)->first();
        $nftCollectionItem = null;

        if ($done) {
            $nftCollectionItem = $done->pivot->nftCollectionItem;
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'done' => (bool) $done,
            'nft_collection_item' => $nftCollectionItem,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
