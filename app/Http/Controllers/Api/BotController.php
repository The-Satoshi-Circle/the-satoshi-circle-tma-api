<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Telegram\TelegramDriver;

class BotController extends Controller
{
    public function __invoke(): void
    {
        DriverManager::loadDriver(TelegramDriver::class);

        $botman = BotManFactory::create([
            'telegram' => [
                'token' => env('TELEGRAM_BOT_TOKEN'),
            ],
        ]);

        $botman->listen();
    }
}
