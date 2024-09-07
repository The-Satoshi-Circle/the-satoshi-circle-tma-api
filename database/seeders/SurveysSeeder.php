<?php

namespace Database\Seeders;

use App\Models\Survey;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SurveysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Survey::query()->create([
            'name' => 'Questionario di ingresso',
        ]);
    }
}
