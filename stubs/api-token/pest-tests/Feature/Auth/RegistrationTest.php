<?php

test('new users can register', function () {
    // Register a new user
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    // Assume the token is returned in the registration response
    $token = $response['token'];

    // Make a request to a protected route with the token
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->get('/api/user');

    // Assert the user is authenticated and the response is successful
    $response->assertStatus(200);
});
