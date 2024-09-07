<?php

use App\Http\Controllers\Api\SurveysController;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;

uses(RefreshDatabase::class);

test('should get surveys', function () {
    $user = User::factory()->create();
    $surveys = Survey::factory()->count(5)->create();

    $response = $this->actingAs($user)
                     ->getJson('/api/surveys');

    $response->assertSuccessful();

    $response->assertJson([
        'surveys' => Arr::map($surveys->toArray(), static function (array $survey) {
            return array_merge(
                $survey,
                ['done' => false]
            );
        }),
    ]);
});

test('should save survey', function () {
    $user = User::factory()->create();
    $survey = Survey::factory()->create();

    $sentSurvey = [
        'question' => fake()->text(10),
        'answer' => fake()->text(10),
    ];

    $response = $this->actingAs($user)
                     ->postJson('/api/surveys/' . $survey->id, [
                         'survey' => $sentSurvey
                     ]);

    $response->assertSuccessful();

    $response->assertJson([
        'status' => 'success',
        'new_coins' => SurveysController::SURVEY_DONE_COINS_AWARD
    ]);
});

test('should return saved survey', function () {
    $user = User::factory()->create();
    $survey = Survey::factory()->create();

    $response = $this->actingAs($user)
                     ->getJson('/api/surveys');

    $response->assertSuccessful();

    $response->assertJson([
        'surveys' => [
            array_merge(
                $survey->toArray(),
                ['done' => false]
            ),
        ],
    ]);

    $response = $this->actingAs($user)
                     ->postJson('/api/surveys/' . $survey->id);

    $response->assertSuccessful();

    $response = $this->actingAs($user)
                     ->getJson('/api/surveys');

    $response->assertSuccessful();

    $response->assertJson([
        'surveys' => [
            array_merge(
                $survey->toArray(),
                ['done' => true]
            ),
        ],
    ]);
});