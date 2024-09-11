<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NFTCollection;
use App\Models\Survey;
use App\Http\Resources\Survey as SurveyResource;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SurveysController extends Controller
{
    public const SURVEY_DONE_COINS_AWARD = 100;

    public function index(): JsonResponse
    {
        return response()->json([
            'surveys' => SurveyResource::collection(Survey::all()),
        ]);
    }

    public function store(Survey $survey, Request $request): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        // assign NFT
        $collection = NFTCollection::first(); // we know that is the first

        $item = $collection->items()->notAssigned()->inRandomOrder()->first();

        $item->user_id = $user->id;
        $item->save();

        $user->surveys()->save($survey, [
            'data' => $request->input('survey'),
            'nft_collection_item_id' => $item->id,
        ]);

        // create a transaction
        $user->transactions()->create([
            'type' => Transaction::TYPE_SURVEY,
            'amount' => self::SURVEY_DONE_COINS_AWARD,
        ]);

        $user->coins += self::SURVEY_DONE_COINS_AWARD;
        $user->save();

        return response()->json([
            'status' => 'success',
            'new_coins' => self::SURVEY_DONE_COINS_AWARD,
            'nft' => $item,
        ]);
    }
}
