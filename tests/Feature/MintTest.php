<?php

use App\Http\Controllers\Api\TapController;
use App\Models\NFTCollection;
use App\Models\NFTCollectionItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('should mint nft', function () {
    $user = User::factory()->create();

    $item = NFTCollectionItem::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->actingAs($user)
        ->postJson("/api/mint/$item->id");

    $response->assertSuccessful();

    $response->assertJson([
        'status' => 'success'
    ]);

    $item->refresh();

    $this->assertSame(1, $item->minted);
});