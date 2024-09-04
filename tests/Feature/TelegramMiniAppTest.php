<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the application returns 403 response for a not registered user', function () {
    $response = $this->post('api/telegram/authorize', [], [
        'Authorization' => 'tma query_id=AAEPnCMAAAAAAA-cIwCvBJkn&user=%7B%22id%22%3A2333711%2C%22first_name%22%3A%22thebatclaudio%22%2C%22last_name%22%3A%22%22%2C%22username%22%3A%22thebatclaudio%22%2C%22language_code%22%3A%22en%22%2C%22is_premium%22%3Atrue%2C%22allows_write_to_pm%22%3Atrue%7D&auth_date=1722035550&hash=949bb2574911e8ae92b4c6c43a4206dff9492fa4497d2604eda7ac0f73250a69',
    ]);

    $response->assertStatus(403);
});

test('the application register a telegram user', function () {
    $response = $this->postJson('api/telegram/register', [
        'initData' => 'query_id=AAEPnCMAAAAAAA-cIwCvBJkn&user=%7B%22id%22%3A2333711%2C%22first_name%22%3A%22thebatclaudio%22%2C%22last_name%22%3A%22%22%2C%22username%22%3A%22thebatclaudio%22%2C%22language_code%22%3A%22en%22%2C%22is_premium%22%3Atrue%2C%22allows_write_to_pm%22%3Atrue%7D&auth_date=1722035550&hash=949bb2574911e8ae92b4c6c43a4206dff9492fa4497d2604eda7ac0f73250a69',
    ]);

    $response->assertStatus(201);
});

test('new user has 0 coins', function () {
    $response = $this->postJson('api/telegram/register', [
        'initData' => 'query_id=AAEPnCMAAAAAAA-cIwCvBJkn&user=%7B%22id%22%3A2333711%2C%22first_name%22%3A%22thebatclaudio%22%2C%22last_name%22%3A%22%22%2C%22username%22%3A%22thebatclaudio%22%2C%22language_code%22%3A%22en%22%2C%22is_premium%22%3Atrue%2C%22allows_write_to_pm%22%3Atrue%7D&auth_date=1722035550&hash=949bb2574911e8ae92b4c6c43a4206dff9492fa4497d2604eda7ac0f73250a69',
    ]);

    $response->assertStatus(201);

    expect($response->json('user.coins'))
        ->toBe(0);
});