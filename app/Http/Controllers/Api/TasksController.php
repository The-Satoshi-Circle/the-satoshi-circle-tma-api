<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\Task as TaskResource;

class TasksController extends Controller
{
    public const TASK_DONE_COINS_AWARD = 100;

    public function index(): JsonResponse
    {
        return response()->json([
            'tasks' => TaskResource::collection(Task::all()),
        ]);
    }

    public function store(Task $task): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $user->tasks()->save($task);

        // create a transaction
        $user->transactions()->create([
            'type' => Transaction::TYPE_TASK,
            'amount' => self::TASK_DONE_COINS_AWARD,
        ]);

        $user->coins += self::TASK_DONE_COINS_AWARD;
        $user->save();

        return response()->json([
            'status' => 'success',
            'new_coins' => self::TASK_DONE_COINS_AWARD,
        ]);
    }
}
