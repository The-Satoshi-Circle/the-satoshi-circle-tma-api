<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TapController extends Controller
{
    public const MAX_DAILY_TAPS = 1000;

    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();
        $todayTaps = $user->transactions()->today()->ofType(Transaction::TYPE_DAILY_TAPS)->sum('amount');

        if ($todayTaps < self::MAX_DAILY_TAPS) {
            DB::transaction(static function () use ($user, $todayTaps, $request) {
                $taps = $request->input('taps');
                if ($todayTaps + $taps > self::MAX_DAILY_TAPS) {
                    $taps = self::MAX_DAILY_TAPS - $todayTaps;
                }

                $user->transactions()->create([
                    'type' => Transaction::TYPE_DAILY_TAPS,
                    'amount' => $taps,
                ]);

                $user->coins += $taps;
                $user->save();
            });

            return response()->json([
                'status' => 'success',
            ]);
        }

        return response()->json([
            'error' => 'daily amount exceeded',
        ], 403);
    }
}
