<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InitController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'daily_taps_limit' => config('game.daily_taps_limit'),
        ]);
    }
}
