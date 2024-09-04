<?php

use App\Http\Controllers\Api\TapController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('should save taps', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->postJson('/api/taps', [
            'taps' => fake()->randomNumber(4)
        ]);

    $response->assertSuccessful();
});

test('should not save taps if daily amount exceeded', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->postJson('/api/taps', [
            'taps' => TapController::MAX_DAILY_TAPS
        ]);

    $response->assertSuccessful();

    $response = $this->actingAs($user)
        ->postJson('/api/taps', [
            'taps' => 1000
        ]);

    $response->assertForbidden();
});