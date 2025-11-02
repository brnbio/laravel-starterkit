<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Hash;

describe('profile update', function () {

    test('users can view the account edit page', function () {
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get(route('account.edit'));
        $response->assertOk();
    });

    test('users can update their profile information', function () {
        /** @var User $user */
        $user = User::factory()->create([
            User::ATTRIBUTE_NAME  => 'Old Name',
            User::ATTRIBUTE_EMAIL => 'old@example.com',
        ]);
        $this->actingAs($user);
        $response = $this->patch(route('account.update'), [
            User::ATTRIBUTE_NAME  => 'New Name',
            User::ATTRIBUTE_EMAIL => 'new@example.com',
        ]);
        $response->assertRedirect(route('account.edit'));
        $user->refresh();
        expect($user->name)
            ->toBe('New Name')
            ->and($user->email)->toBe('new@example.com');
    });

    test('name is required for profile update', function () {
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->patch(route('account.update'), [
            User::ATTRIBUTE_EMAIL => 'test@example.com',
        ]);
        $response->assertSessionHasErrors(User::ATTRIBUTE_NAME);
    });

    test('email is required for profile update', function () {
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->patch(route('account.update'), [
            User::ATTRIBUTE_NAME => 'Test User',
        ]);
        $response->assertSessionHasErrors(User::ATTRIBUTE_EMAIL);
    });

    test('email must be valid for profile update', function () {
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->patch(route('account.update'), [
            User::ATTRIBUTE_NAME  => 'Test User',
            User::ATTRIBUTE_EMAIL => 'not-an-email',
        ]);
        $response->assertSessionHasErrors(User::ATTRIBUTE_EMAIL);
    });

    test('email must be unique for profile update', function () {
        /** @var User $existingUser */
        $existingUser = User::factory()->create([
            User::ATTRIBUTE_EMAIL => 'existing@example.com',
        ]);
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->patch(route('account.update'), [
            User::ATTRIBUTE_NAME  => 'Test User',
            User::ATTRIBUTE_EMAIL => 'existing@example.com',
        ]);
        $response->assertSessionHasErrors(User::ATTRIBUTE_EMAIL);
    });

    test('user can keep their own email when updating profile', function () {
        /** @var User $user */
        $user = User::factory()->create([
            User::ATTRIBUTE_EMAIL => 'test@example.com',
        ]);
        $this->actingAs($user);
        $response = $this->patch(route('account.update'), [
            User::ATTRIBUTE_NAME  => 'New Name',
            User::ATTRIBUTE_EMAIL => 'test@example.com',
        ]);
        $response->assertRedirect(route('account.edit'));
        $response->assertSessionHasNoErrors();
    });

    test('guests cannot update profile', function () {
        $response = $this->patch(route('account.update'), [
            User::ATTRIBUTE_NAME  => 'Test User',
            User::ATTRIBUTE_EMAIL => 'test@example.com',
        ]);
        $response->assertRedirect(route('login'));
    });
});

describe('password update', function () {

    test('users can view the password edit page', function () {
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get(route('account.password'));
        $response->assertOk();
    });

    test('users can update their password', function () {
        /** @var User $user */
        $user = User::factory()->create([
            User::ATTRIBUTE_PASSWORD => 'OldPassword123!',
        ]);
        $this->actingAs($user);
        $response = $this->patch(route('account.password.update'), [
            'current_password'       => 'OldPassword123!',
            User::ATTRIBUTE_PASSWORD => 'NewPassword123!',
            'password_confirmation'  => 'NewPassword123!',
        ]);
        $response->assertRedirect(route('account.password'));
        expect(Hash::check('NewPassword123!', $user->fresh()->password))->toBeTrue();
    });

    test('current password must be correct', function () {
        /** @var User $user */
        $user = User::factory()->create([
            User::ATTRIBUTE_PASSWORD => 'OldPassword123!',
        ]);
        $this->actingAs($user);
        $response = $this->patch(route('account.password.update'), [
            'current_password'       => 'WrongPassword123!',
            User::ATTRIBUTE_PASSWORD => 'NewPassword123!',
            'password_confirmation'  => 'NewPassword123!',
        ]);
        $response->assertSessionHasErrors('current_password');
    });

    test('current password is required', function () {
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->patch(route('account.password.update'), [
            User::ATTRIBUTE_PASSWORD => 'NewPassword123!',
            'password_confirmation'  => 'NewPassword123!',
        ]);
        $response->assertSessionHasErrors('current_password');
    });

    test('new password is required', function () {
        /** @var User $user */
        $user = User::factory()->create([
            User::ATTRIBUTE_PASSWORD => 'OldPassword123!',
        ]);
        $this->actingAs($user);
        $response = $this->patch(route('account.password.update'), [
            'current_password' => 'OldPassword123!',
        ]);
        $response->assertSessionHasErrors(User::ATTRIBUTE_PASSWORD);
    });

    test('new password must be confirmed', function () {
        /** @var User $user */
        $user = User::factory()->create([
            User::ATTRIBUTE_PASSWORD => 'OldPassword123!',
        ]);
        $this->actingAs($user);
        $response = $this->patch(route('account.password.update'), [
            'current_password'       => 'OldPassword123!',
            User::ATTRIBUTE_PASSWORD => 'NewPassword123!',
            'password_confirmation'  => 'DifferentPassword123!',
        ]);
        $response->assertSessionHasErrors(User::ATTRIBUTE_PASSWORD);
    });

    test('new password must be different from current password', function () {
        /** @var User $user */
        $user = User::factory()->create([
            User::ATTRIBUTE_PASSWORD => 'SamePassword123!',
        ]);
        $this->actingAs($user);
        $response = $this->patch(route('account.password.update'), [
            'current_password'       => 'SamePassword123!',
            User::ATTRIBUTE_PASSWORD => 'SamePassword123!',
            'password_confirmation'  => 'SamePassword123!',
        ]);
        $response->assertSessionHasErrors(User::ATTRIBUTE_PASSWORD);
    });

    test('guests cannot update password', function () {
        $response = $this->patch(route('account.password.update'), [
            'current_password'       => 'OldPassword123!',
            User::ATTRIBUTE_PASSWORD => 'NewPassword123!',
            'password_confirmation'  => 'NewPassword123!',
        ]);
        $response->assertRedirect(route('login'));
    });
});

describe('account deletion', function () {

    test('users can delete their account with correct password', function () {
        /** @var User $user */
        $user = User::factory()->create([
            User::ATTRIBUTE_PASSWORD => 'Password123!',
        ]);
        $this->actingAs($user);
        $response = $this->delete(route('account.destroy'), [
            User::ATTRIBUTE_PASSWORD => 'Password123!',
        ]);
        $response->assertRedirect('/');
        $this->assertGuest();
        $this->assertDatabaseMissing(User::TABLE, [
            'id' => $user->id,
        ]);
    });

    test('password is required for account deletion', function () {
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->delete(route('account.destroy'), []);
        $response->assertSessionHasErrors(User::ATTRIBUTE_PASSWORD);
        $this->assertDatabaseHas(User::TABLE, [
            'id' => $user->id,
        ]);
    });

    test('correct password is required for account deletion', function () {
        /** @var User $user */
        $user = User::factory()->create([
            User::ATTRIBUTE_PASSWORD => 'Password123!',
        ]);
        $this->actingAs($user);
        $response = $this->delete(route('account.destroy'), [
            User::ATTRIBUTE_PASSWORD => 'WrongPassword123!',
        ]);
        $response->assertSessionHasErrors(User::ATTRIBUTE_PASSWORD);
        $this->assertDatabaseHas(User::TABLE, [
            'id' => $user->id,
        ]);
    });

    test('guests cannot delete account', function () {
        $response = $this->delete(route('account.destroy'), [
            User::ATTRIBUTE_PASSWORD => 'Password123!',
        ]);
        $response->assertRedirect(route('login'));
    });
});

describe('two factor authentication', function () {

    test('users can view the 2fa page', function () {
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get(route('account.2fa'));
        $response->assertOk();
    });

    test('guests cannot view the 2fa page', function () {
        $response = $this->get(route('account.2fa'));
        $response->assertRedirect(route('login'));
    });
});

describe('appearance', function () {

    test('users can view the appearance page', function () {
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get(route('account.appearance'));
        $response->assertOk();
    });

    test('guests cannot view the appearance page', function () {
        $response = $this->get(route('account.appearance'));
        $response->assertRedirect(route('login'));
    });
});
