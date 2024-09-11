<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Task::query()->create([
            'name' => 'Join Chat',
            'url' => 'https://t.me/thesatoshicircle',
            'type' => Task::TYPE_GROUP
        ]);

        Task::query()->create([
            'name' => 'Join News',
            'url' => 'https://t.me/thesatoshicirclenews',
            'type' => Task::TYPE_LINK
        ]);

        Task::query()->create([
            'name' => 'Follow X',
            'url' => 'https://x.com/satoshiscircle',
            'type' => Task::TYPE_LINK
        ]);
    }
}
