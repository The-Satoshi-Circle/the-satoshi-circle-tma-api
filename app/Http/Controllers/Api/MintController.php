<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NFTCollectionItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MintController extends Controller
{
    public function __invoke(NFTCollectionItem $item): JsonResponse
    {
        if (auth()->user()->id !== $item->user_id) {
            abort(403);
        }

        $item->minted = true;
        $item->save();

        return response()->json([
            'status' => 'success',
        ]);
    }
}
