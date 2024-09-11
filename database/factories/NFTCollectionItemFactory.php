<?php

namespace Database\Factories;

use App\Models\NFTCollection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class NFTCollectionItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->text(20),
            'description' => fake()->text(80),
            'image' => fake()->url(),
            'minted' => false,
            'user_id' => null,
            'nft_collection_id' => NFTCollection::factory()->create()->id,
        ];
    }
}
