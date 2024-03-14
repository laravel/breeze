<?php

use App\Models\User;
use function Pest\Laravel\{post, get, assertGuest, assertStatus, withHeaders};

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('users can authenticate with token', function () {
    $user = User::factory()->create();

    $response = post('/login', [
        'email' => $user->email,
        'password' => 'password', // Use the default password or your actual password
    ]);

    $response->assertStatus(200);

    $token = $response->json('token');
    \Illuminate\Testing\Assert::assertNotNull($token);

    $response = withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->get('/api/user'); // Change this to your API endpoint

    $response->assertStatus(200);
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $response = withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->post('/logout');

    $response->assertStatus(302);
});
