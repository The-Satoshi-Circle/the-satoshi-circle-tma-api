<?php

use App\Http\Controllers\Api\SurveysController;
use App\Models\NFTCollection;
use App\Models\NFTCollectionItem;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;

uses(RefreshDatabase::class);

beforeEach(function () {
    $collection = NFTCollection::factory()->create();
    NFTCollectionItem::factory()->count(10)->create([
        'nft_collection_id' => $collection->id,
    ]);
});

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

test('should get nft after save survey', function () {
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
    $data = $response->json();

    $this->assertNotNull($data['nft']);
    $this->assertNotNull($data['nft']['id']);
    $this->assertSame(0, $data['nft']['minted']);
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

test('should return nft for saved survey', function () {
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

    $data = $response->json('surveys.0');

    $this->assertNotNull($data['nft_collection_item']);
    $this->assertNotNull($data['nft_collection_item']['id']);
    $this->assertSame(0, $data['nft_collection_item']['minted']);
});