<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

describe('authentication', function () {

    test('users can view the login page', function () {
        $response = $this->get(route('login'));
        $response->assertOk();
    });

    test('users can authenticate with valid credentials', function () {
        /** @var User $user */
        $user = User::factory()->create([
            User::ATTRIBUTE_PASSWORD => 'password123',
        ]);
        $response = $this->post(route('login'), [
            User::ATTRIBUTE_EMAIL    => $user->email,
            User::ATTRIBUTE_PASSWORD => 'password123',
        ]);
        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    });

    test('users cannot authenticate with invalid password', function () {
        /** @var User $user */
        $user = User::factory()->create([
            User::ATTRIBUTE_PASSWORD => 'password123',
        ]);
        $response = $this->post(route('login'), [
            User::ATTRIBUTE_EMAIL    => $user->email,
            User::ATTRIBUTE_PASSWORD => 'wrong-password',
        ]);
        $response->assertSessionHasErrors();
        $this->assertGuest();
    });

    test('users cannot authenticate with non-existent email', function () {
        $response = $this->post(route('login'), [
            User::ATTRIBUTE_EMAIL    => 'nonexistent@example.com',
            User::ATTRIBUTE_PASSWORD => 'password123',
        ]);
        $response->assertSessionHasErrors();
        $this->assertGuest();
    });

    test('email field is required for login', function () {
        $response = $this->post(route('login'), [
            User::ATTRIBUTE_PASSWORD => 'password123',
        ]);
        $response->assertSessionHasErrors(User::ATTRIBUTE_EMAIL);
        $this->assertGuest();
    });

    test('password field is required for login', function () {
        /** @var User $user */
        $user = User::factory()->create();
        $response = $this->post(route('login'), [
            User::ATTRIBUTE_EMAIL => $user->email,
        ]);
        $response->assertSessionHasErrors(User::ATTRIBUTE_PASSWORD);
        $this->assertGuest();
    });

    test('authenticated users can logout', function () {
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->post(route('logout'));
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    });

    test('guests cannot access protected routes', function () {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    });

    test('two factor authentication rate limiting is applied', function () {
        /** @var User $user */
        $user = User::factory()->create([
            User::ATTRIBUTE_PASSWORD => 'Password123!',
        ]);
        $user->forceFill([
            'two_factor_secret' => encrypt('secret'),
            'two_factor_recovery_codes' => encrypt(json_encode(['recovery-code'])),
        ])->save();
        $this->post(route('login'), [
            User::ATTRIBUTE_EMAIL => $user->email,
            User::ATTRIBUTE_PASSWORD => 'Password123!',
        ]);
        for ($i = 0; $i < 5; $i++) {
            $this->post('/two-factor-challenge', [
                'code' => 'invalid-code',
            ]);
        }
        $response = $this->post('/two-factor-challenge', [
            'code' => 'invalid-code',
        ]);
        $response->assertStatus(429);
    });
});

describe('registration', function () {

    test('users can view the registration page', function () {
        $response = $this->get(route('register'));
        $response->assertOk();
    });

    test('users can register with valid information', function () {
        $response = $this->post(route('register'), [
            User::ATTRIBUTE_NAME     => 'Test User',
            User::ATTRIBUTE_EMAIL    => 'test@example.com',
            User::ATTRIBUTE_PASSWORD => 'Password123!',
        ]);
        $response->assertRedirect(route('account.edit'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas(User::TABLE, [
            User::ATTRIBUTE_NAME  => 'Test User',
            User::ATTRIBUTE_EMAIL => 'test@example.com',
        ]);
    });

    test('users are automatically logged in after registration', function () {
        $this->post(route('register'), [
            User::ATTRIBUTE_NAME     => 'Test User',
            User::ATTRIBUTE_EMAIL    => 'test@example.com',
            User::ATTRIBUTE_PASSWORD => 'Password123!',
        ]);
        $this->assertAuthenticated();
        expect(auth()->user()->email)->toBe('test@example.com');
    });

    test('name is required for registration', function () {
        $response = $this->post(route('register'), [
            User::ATTRIBUTE_EMAIL    => 'test@example.com',
            User::ATTRIBUTE_PASSWORD => 'Password123!',
        ]);
        $response->assertSessionHasErrors(User::ATTRIBUTE_NAME);
        $this->assertGuest();
    });

    test('email is required for registration', function () {
        $response = $this->post(route('register'), [
            User::ATTRIBUTE_NAME     => 'Test User',
            User::ATTRIBUTE_PASSWORD => 'Password123!',
        ]);
        $response->assertSessionHasErrors(User::ATTRIBUTE_EMAIL);
        $this->assertGuest();
    });

    test('email must be valid email address', function () {
        $response = $this->post(route('register'), [
            User::ATTRIBUTE_NAME     => 'Test User',
            User::ATTRIBUTE_EMAIL    => 'not-an-email',
            User::ATTRIBUTE_PASSWORD => 'Password123!',
        ]);
        $response->assertSessionHasErrors(User::ATTRIBUTE_EMAIL);
        $this->assertGuest();
    });

    test('email must be unique', function () {
        /** @var User $existingUser */
        $existingUser = User::factory()->create([
            User::ATTRIBUTE_EMAIL => 'test@example.com',
        ]);
        $response = $this->post(route('register'), [
            User::ATTRIBUTE_NAME     => 'Test User',
            User::ATTRIBUTE_EMAIL    => 'test@example.com',
            User::ATTRIBUTE_PASSWORD => 'Password123!',
        ]);
        $response->assertSessionHasErrors(User::ATTRIBUTE_EMAIL);
        $this->assertGuest();
    });

    test('password is required for registration', function () {
        $response = $this->post(route('register'), [
            User::ATTRIBUTE_NAME  => 'Test User',
            User::ATTRIBUTE_EMAIL => 'test@example.com',
        ]);
        $response->assertSessionHasErrors(User::ATTRIBUTE_PASSWORD);
        $this->assertGuest();
    });

    test('authenticated users cannot access registration page', function () {
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get(route('register'));
        $response->assertRedirect(route('dashboard'));
    });
});

describe('password reset', function () {

    test('users can view the forgot password page', function () {
        $response = $this->get(route('password.request'));
        $response->assertOk();
    });

    test('users can request a password reset link', function () {
        Notification::fake();
        /** @var User $user */
        $user = User::factory()->create();
        $response = $this->post(route('password.email'), [
            User::ATTRIBUTE_EMAIL => $user->email,
        ]);
        $response->assertRedirect(route('password.request'));
        Notification::assertSentTo($user, ResetPassword::class);
    });

    test('email is required for password reset request', function () {
        $response = $this->post(route('password.email'), []);
        $response->assertSessionHasErrors(User::ATTRIBUTE_EMAIL);
    });

    test('email must be valid for password reset request', function () {
        $response = $this->post(route('password.email'), [
            User::ATTRIBUTE_EMAIL => 'not-an-email',
        ]);
        $response->assertSessionHasErrors(User::ATTRIBUTE_EMAIL);
    });

    test('password reset fails for non-existent email', function () {
        $response = $this->post(route('password.email'), [
            User::ATTRIBUTE_EMAIL => 'nonexistent@example.com',
        ]);
        $response->assertSessionHasErrors(User::ATTRIBUTE_EMAIL);
    });

    test('users can view the reset password page with valid token', function () {
        /** @var User $user */
        $user = User::factory()->create();
        $token = Password::broker()->createToken($user);
        $response = $this->get(route('password.reset', [User::ATTRIBUTE_RESET_TOKEN => $token]) . '?' . User::ATTRIBUTE_EMAIL . '=' . $user->email);
        $response->assertOk();
    });

    test('users can reset their password with valid token', function () {
        /** @var User $user */
        $user = User::factory()->create();
        $token = Password::broker()->createToken($user);
        $response = $this->post(route('password.store'), [
            User::ATTRIBUTE_RESET_TOKEN => $token,
            User::ATTRIBUTE_EMAIL       => $user->email,
            User::ATTRIBUTE_PASSWORD    => 'NewPassword123!',
            'password_confirmation'     => 'NewPassword123!',
        ]);
        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
        expect(Hash::check('NewPassword123!', $user->fresh()->password))->toBeTrue();
    });

    test('password reset fails with invalid token', function () {
        /** @var User $user */
        $user = User::factory()->create();
        $response = $this->post(route('password.store'), [
            User::ATTRIBUTE_RESET_TOKEN => 'invalid-token',
            User::ATTRIBUTE_EMAIL       => $user->email,
            User::ATTRIBUTE_PASSWORD    => 'NewPassword123!',
            'password_confirmation'     => 'NewPassword123!',
        ]);
        $response->assertSessionHasErrors(User::ATTRIBUTE_EMAIL);
        $this->assertGuest();
    });

    test('password reset requires password confirmation', function () {
        /** @var User $user */
        $user = User::factory()->create();
        $token = Password::broker()->createToken($user);
        $response = $this->post(route('password.store'), [
            User::ATTRIBUTE_RESET_TOKEN => $token,
            User::ATTRIBUTE_EMAIL       => $user->email,
            User::ATTRIBUTE_PASSWORD    => 'NewPassword123!',
            'password_confirmation'     => 'DifferentPassword123!',
        ]);
        $response->assertSessionHasErrors(User::ATTRIBUTE_PASSWORD);
    });

    test('password reset requires minimum length', function () {
        /** @var User $user */
        $user = User::factory()->create();
        $token = Password::broker()->createToken($user);
        $response = $this->post(route('password.store'), [
            User::ATTRIBUTE_RESET_TOKEN => $token,
            User::ATTRIBUTE_EMAIL       => $user->email,
            User::ATTRIBUTE_PASSWORD    => 'short',
            'password_confirmation'     => 'short',
        ]);
        $response->assertSessionHasErrors(User::ATTRIBUTE_PASSWORD);
    });

    test('password reset requires email', function () {
        /** @var User $user */
        $user = User::factory()->create();
        $token = Password::broker()->createToken($user);
        $response = $this->post(route('password.store'), [
            User::ATTRIBUTE_RESET_TOKEN => $token,
            User::ATTRIBUTE_PASSWORD    => 'NewPassword123!',
            'password_confirmation'     => 'NewPassword123!',
        ]);
        $response->assertSessionHasErrors(User::ATTRIBUTE_EMAIL);
    });

    test('password reset requires token', function () {
        /** @var User $user */
        $user = User::factory()->create();
        $response = $this->post(route('password.store'), [
            User::ATTRIBUTE_EMAIL    => $user->email,
            User::ATTRIBUTE_PASSWORD => 'NewPassword123!',
            'password_confirmation'  => 'NewPassword123!',
        ]);
        $response->assertSessionHasErrors();
    });

    test('authenticated users cannot access forgot password page', function () {
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get(route('password.request'));
        $response->assertRedirect(route('dashboard'));
    });
});