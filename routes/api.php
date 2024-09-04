<?php

use App\Http\Controllers\Api\InitController;
use App\Http\Controllers\Api\TapController;
use App\Http\Controllers\Api\TelegramMiniAppController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/init', InitController::class);

Route::post('/telegram/authorize', [TelegramMiniAppController::class, 'validateAuthorizingRequest']);
Route::post('/telegram/register', [TelegramMiniAppController::class, 'register']);

Route::group(['middleware' => ['auth:sanctum']], static function () {
    Route::post('taps', TapController::class);
});
