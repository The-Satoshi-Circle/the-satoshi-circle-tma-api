<?php

use App\Http\Controllers\Api\BotController;
use Illuminate\Support\Facades\Route;

Route::post("/listen", BotController::class);