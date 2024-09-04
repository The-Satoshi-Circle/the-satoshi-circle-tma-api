<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use JsonException;
use OxMohsen\TgBot\Validate;

class TelegramMiniAppController extends Controller
{
    public function validateAuthorizingRequest(Request $request): JsonResponse
    {
        $authorization = $request->header('Authorization');

        if (!$authorization) {
            abort(404);
        }

        $initData = Str::replace('tma ', '', $authorization);
        if (config('env') === 'production' && !Validate::isSafe(Config::get('telegram.bot.token'), $initData)) {
            abort(401);
        }

        $userData = $this->extractUserData($initData);

        if (!$user = User::where('telegram_id', $userData['id'])->first()) {
            return response()->json([
                'status' => 'not_registered',
            ], 403);
        }

        $token = $user->createToken('telegram');
        $plainTextToken = $token->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $plainTextToken,
        ]);
    }

    /**
     * @throws JsonException
     */
    private function extractUserData(string $initData): ?array
    {
        $initDataArray = explode('&', rawurldecode($initData));
        $needle = 'user=';
        $hash = '';

        foreach ($initDataArray as &$data) {
            if (str_starts_with($data, $needle)) {
                $hash = substr_replace($data, '', 0, \strlen($needle));

                return json_decode($hash, true, 512, JSON_THROW_ON_ERROR);
            }
        }

        return null;
    }

    /**
     * @throws JsonException
     */
    public function register(Request $request): JsonResponse
    {
        $initData = $request->input('initData');

        if (config('env') === 'production' && !Validate::isSafe(Config::get('telegram.bot.token'), $initData)) {
            abort(401);
        }

        $userData = $this->extractUserData($initData);

        if (User::query()->where('telegram_id', $userData['id'])->exists()) {
            return response()->json([
                'status' => 'already_registered',
            ], 401);
        }

        $userData['telegram_id'] = $userData['id'];

        $user = User::query()->create($userData);

        $token = $user->createToken('telegram');

        $user->refresh();

        return response()->json([
            'user' => $user,
            'token' => $token->plainTextToken,
        ], 201);
    }
}
