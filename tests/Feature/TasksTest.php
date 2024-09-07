<?php

use App\Http\Controllers\Api\TasksController;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;

uses(RefreshDatabase::class);

test('should get tasks', function () {
    $user = User::factory()->create();
    $tasks = Task::factory()->count(5)->create();

    $response = $this->actingAs($user)
        ->getJson('/api/tasks');

    $response->assertSuccessful();

    $response->assertJson([
        'tasks' => Arr::map($tasks->toArray(), static function (array $task) {
            return array_merge(
                $task,
                ['done' => false]
            );
        }),
    ]);
});

test('should save task', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create();

    $response = $this->actingAs($user)
        ->postJson('/api/tasks/'.$task->id);

    $response->assertSuccessful();

    $response->assertJson([
        'status' => 'success',
        'new_coins' => TasksController::TASK_DONE_COINS_AWARD
    ]);
});

test('should return saved task', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create();

    $response = $this->actingAs($user)
        ->getJson('/api/tasks');

    $response->assertSuccessful();

    $response->assertJson([
        'tasks' => [
            array_merge(
                $task->toArray(),
                ['done' => false]
            )
        ]
    ]);

    $response = $this->actingAs($user)
        ->postJson('/api/tasks/'.$task->id);

    $response->assertSuccessful();

    $response = $this->actingAs($user)
        ->getJson('/api/tasks');

    $response->assertSuccessful();

    $response->assertJson([
        'tasks' => [
            array_merge(
                $task->toArray(),
                ['done' => true]
            )
        ]
    ]);
});