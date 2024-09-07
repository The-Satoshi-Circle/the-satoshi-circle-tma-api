<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Http\Resources\Survey as SurveyResource;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

        $user->surveys()->save($survey, [
            'data' => $request->input('survey'),
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
        ]);
    }
}
