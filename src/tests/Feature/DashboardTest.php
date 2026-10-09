<?php

declare(strict_types=1);

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

describe('dashboard', function (): void {

    test('authenticated users can visit the dashboard', function (): void {
        actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page->component('dashboard'));
    });

    test('guests are redirected to the login page', function (): void {
        get(route('dashboard'))
            ->assertRedirect(route('login'));
    });
});
