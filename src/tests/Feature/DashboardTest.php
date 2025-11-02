<?php

use App\Models\User;

beforeEach(function () {
    $user = User::factory()->create();
    $this->actingAs($user);
});

test('authenticated users can visit the dashboard', function () {
    $response = $this->get(route('dashboard'));
    $response->assertOk();
});