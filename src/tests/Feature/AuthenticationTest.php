<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\assertAuthenticatedAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

describe('authentication', function (): void {

    test('users can view the login page', function (): void {
        get(route('login'))
            ->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page->component('auth/login'));
    });

    test('users can authenticate with valid credentials', function (): void {
        $user = User::factory()->create([
            User::ATTRIBUTE_PASSWORD => 'password123',
        ]);

        post(route('login'), [
            User::ATTRIBUTE_EMAIL    => $user->email,
            User::ATTRIBUTE_PASSWORD => 'password123',
        ])->assertRedirect(route('dashboard'));

        assertAuthenticatedAs($user);
    });

    test('login validates credentials', function (array $data, ?string $field): void {
        User::factory()->create([
            User::ATTRIBUTE_EMAIL    => 'user@example.com',
            User::ATTRIBUTE_PASSWORD => 'password123',
        ]);

        post(route('login'), $data)
            ->assertSessionHasErrors($field ?? []);

        assertGuest();
    })->with([
        'invalid password' => [
            [User::ATTRIBUTE_EMAIL => 'user@example.com', User::ATTRIBUTE_PASSWORD => 'wrong-password'],
            null,
        ],
        'non-existent email' => [
            [User::ATTRIBUTE_EMAIL => 'nonexistent@example.com', User::ATTRIBUTE_PASSWORD => 'password123'],
            null,
        ],
        'email is required' => [
            [User::ATTRIBUTE_PASSWORD => 'password123'],
            User::ATTRIBUTE_EMAIL,
        ],
        'password is required' => [
            [User::ATTRIBUTE_EMAIL => 'user@example.com'],
            User::ATTRIBUTE_PASSWORD,
        ],
    ]);

    test('authenticated users can logout', function (): void {
        actingAs(User::factory()->create())
            ->post(route('logout'))
            ->assertRedirect(route('login'));

        assertGuest();
    });

    test('guests cannot access protected routes', function (): void {
        get(route('dashboard'))
            ->assertRedirect(route('login'));
    });

    test('two factor authentication rate limiting is applied', function (): void {
        $user = User::factory()->create([
            User::ATTRIBUTE_PASSWORD => 'Password123!',
        ]);
        $user->forceFill([
            'two_factor_secret'         => encrypt('secret'),
            'two_factor_recovery_codes' => encrypt(json_encode(['recovery-code'])),
        ])->save();

        post(route('login'), [
            User::ATTRIBUTE_EMAIL    => $user->email,
            User::ATTRIBUTE_PASSWORD => 'Password123!',
        ]);

        for ($i = 0; $i < 5; $i++) {
            post('/two-factor-challenge', ['code' => 'invalid-code']);
        }

        post('/two-factor-challenge', ['code' => 'invalid-code'])
            ->assertTooManyRequests();
    });
});

describe('registration', function (): void {

    test('users can view the registration page', function (): void {
        get(route('register'))
            ->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page->component('auth/register'));
    });

    test('users can register with valid information', function (): void {
        post(route('register'), [
            User::ATTRIBUTE_NAME     => 'Test User',
            User::ATTRIBUTE_EMAIL    => 'test@example.com',
            User::ATTRIBUTE_PASSWORD => 'Password123!',
        ])->assertRedirect(route('account.edit'));

        assertAuthenticated();
        assertDatabaseHas(User::TABLE, [
            User::ATTRIBUTE_NAME  => 'Test User',
            User::ATTRIBUTE_EMAIL => 'test@example.com',
        ]);
        expect(auth()->user()?->email)->toBe('test@example.com');
    });

    test('registration validates input', function (array $data, string $field): void {
        User::factory()->create([
            User::ATTRIBUTE_EMAIL => 'existing@example.com',
        ]);

        post(route('register'), $data)
            ->assertSessionHasErrors($field);

        assertGuest();
    })->with([
        'name is required' => [
            [User::ATTRIBUTE_EMAIL => 'test@example.com', User::ATTRIBUTE_PASSWORD => 'Password123!'],
            User::ATTRIBUTE_NAME,
        ],
        'email is required' => [
            [User::ATTRIBUTE_NAME => 'Test User', User::ATTRIBUTE_PASSWORD => 'Password123!'],
            User::ATTRIBUTE_EMAIL,
        ],
        'email must be valid' => [
            [User::ATTRIBUTE_NAME => 'Test User', User::ATTRIBUTE_EMAIL => 'not-an-email', User::ATTRIBUTE_PASSWORD => 'Password123!'],
            User::ATTRIBUTE_EMAIL,
        ],
        'email must be unique' => [
            [User::ATTRIBUTE_NAME => 'Test User', User::ATTRIBUTE_EMAIL => 'existing@example.com', User::ATTRIBUTE_PASSWORD => 'Password123!'],
            User::ATTRIBUTE_EMAIL,
        ],
        'password is required' => [
            [User::ATTRIBUTE_NAME => 'Test User', User::ATTRIBUTE_EMAIL => 'test@example.com'],
            User::ATTRIBUTE_PASSWORD,
        ],
    ]);

    test('authenticated users cannot access registration page', function (): void {
        actingAs(User::factory()->create())
            ->get(route('register'))
            ->assertRedirect(route('dashboard'));
    });
});

describe('password reset', function (): void {

    test('users can view the forgot password page', function (): void {
        get(route('password.request'))
            ->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page->component('auth/forgot-password'));
    });

    test('users can request a password reset link', function (): void {
        Notification::fake();
        $user = User::factory()->create();

        post(route('password.email'), [
            User::ATTRIBUTE_EMAIL => $user->email,
        ])->assertRedirect(route('password.request'));

        Notification::assertSentTo($user, ResetPassword::class);
    });

    test('password reset request validates email', function (array $data): void {
        post(route('password.email'), $data)
            ->assertSessionHasErrors(User::ATTRIBUTE_EMAIL);
    })->with([
        'email is required'     => [[]],
        'email must be valid'   => [[User::ATTRIBUTE_EMAIL => 'not-an-email']],
        'email must exist'      => [[User::ATTRIBUTE_EMAIL => 'nonexistent@example.com']],
    ]);

    test('users can view the reset password page with valid token', function (): void {
        $user = User::factory()->create();
        $token = Password::broker()->createToken($user);

        get(route('password.reset', [
            User::ATTRIBUTE_RESET_TOKEN => $token,
            User::ATTRIBUTE_EMAIL       => $user->email,
        ]))
            ->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page->component('auth/reset-password'));
    });

    test('users can reset their password with valid token', function (): void {
        $user = User::factory()->create();
        $token = Password::broker()->createToken($user);

        post(route('password.store'), [
            User::ATTRIBUTE_RESET_TOKEN => $token,
            User::ATTRIBUTE_EMAIL       => $user->email,
            User::ATTRIBUTE_PASSWORD    => 'NewPassword123!',
            'password_confirmation'     => 'NewPassword123!',
        ])->assertRedirect(route('dashboard'));

        assertAuthenticatedAs($user);
        expect(Hash::check('NewPassword123!', $user->refresh()->password))->toBeTrue();
    });

    test('password reset fails with invalid token', function (): void {
        $user = User::factory()->create();

        post(route('password.store'), [
            User::ATTRIBUTE_RESET_TOKEN => 'invalid-token',
            User::ATTRIBUTE_EMAIL       => $user->email,
            User::ATTRIBUTE_PASSWORD    => 'NewPassword123!',
            'password_confirmation'     => 'NewPassword123!',
        ])->assertSessionHasErrors(User::ATTRIBUTE_EMAIL);

        assertGuest();
    });

    test('password reset validates input', function (array $overrides, array $without, ?string $field): void {
        $user = User::factory()->create();
        $data = array_merge([
            User::ATTRIBUTE_RESET_TOKEN => Password::broker()->createToken($user),
            User::ATTRIBUTE_EMAIL       => $user->email,
            User::ATTRIBUTE_PASSWORD    => 'NewPassword123!',
            'password_confirmation'     => 'NewPassword123!',
        ], $overrides);

        post(route('password.store'), array_diff_key($data, array_flip($without)))
            ->assertSessionHasErrors($field ?? []);
    })->with([
        'password must be confirmed' => [
            ['password_confirmation' => 'DifferentPassword123!'],
            [],
            User::ATTRIBUTE_PASSWORD,
        ],
        'password requires minimum length' => [
            [User::ATTRIBUTE_PASSWORD => 'short', 'password_confirmation' => 'short'],
            [],
            User::ATTRIBUTE_PASSWORD,
        ],
        'email is required' => [
            [],
            [User::ATTRIBUTE_EMAIL],
            User::ATTRIBUTE_EMAIL,
        ],
        'token is required' => [
            [],
            [User::ATTRIBUTE_RESET_TOKEN],
            null,
        ],
    ]);

    test('authenticated users cannot access forgot password page', function (): void {
        actingAs(User::factory()->create())
            ->get(route('password.request'))
            ->assertRedirect(route('dashboard'));
    });
});
