<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\patch;

describe('profile update', function (): void {

    test('users can view the account edit page', function (): void {
        actingAs(User::factory()->create())
            ->get(route('account.edit'))
            ->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page->component('account/edit'));
    });

    test('users can update their profile information', function (): void {
        $user = User::factory()->create([
            User::ATTRIBUTE_NAME  => 'Old Name',
            User::ATTRIBUTE_EMAIL => 'old@example.com',
        ]);

        actingAs($user)
            ->patch(route('account.update'), [
                User::ATTRIBUTE_NAME  => 'New Name',
                User::ATTRIBUTE_EMAIL => 'new@example.com',
            ])
            ->assertRedirect(route('account.edit'));

        $user->refresh();
        expect($user->name)->toBe('New Name')
            ->and($user->email)->toBe('new@example.com');
    });

    test('user can keep their own email when updating profile', function (): void {
        $user = User::factory()->create([
            User::ATTRIBUTE_EMAIL => 'test@example.com',
        ]);

        actingAs($user)
            ->patch(route('account.update'), [
                User::ATTRIBUTE_NAME  => 'New Name',
                User::ATTRIBUTE_EMAIL => 'test@example.com',
            ])
            ->assertRedirect(route('account.edit'))
            ->assertSessionHasNoErrors();
    });

    test('profile update validates input', function (array $data, string $field): void {
        User::factory()->create([
            User::ATTRIBUTE_EMAIL => 'existing@example.com',
        ]);

        actingAs(User::factory()->create())
            ->patch(route('account.update'), $data)
            ->assertSessionHasErrors($field);
    })->with([
        'name is required' => [
            [User::ATTRIBUTE_EMAIL => 'test@example.com'],
            User::ATTRIBUTE_NAME,
        ],
        'email is required' => [
            [User::ATTRIBUTE_NAME => 'Test User'],
            User::ATTRIBUTE_EMAIL,
        ],
        'email must be valid' => [
            [User::ATTRIBUTE_NAME => 'Test User', User::ATTRIBUTE_EMAIL => 'not-an-email'],
            User::ATTRIBUTE_EMAIL,
        ],
        'email must be unique' => [
            [User::ATTRIBUTE_NAME => 'Test User', User::ATTRIBUTE_EMAIL => 'existing@example.com'],
            User::ATTRIBUTE_EMAIL,
        ],
    ]);

    test('guests cannot update profile', function (): void {
        patch(route('account.update'), [
            User::ATTRIBUTE_NAME  => 'Test User',
            User::ATTRIBUTE_EMAIL => 'test@example.com',
        ])->assertRedirect(route('login'));
    });
});

describe('password update', function (): void {

    test('users can view the password edit page', function (): void {
        actingAs(User::factory()->create())
            ->get(route('account.password'))
            ->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page->component('account/editPassword'));
    });

    test('users can update their password', function (): void {
        $user = User::factory()->create([
            User::ATTRIBUTE_PASSWORD => 'OldPassword123!',
        ]);

        actingAs($user)
            ->patch(route('account.password.update'), [
                'current_password'       => 'OldPassword123!',
                User::ATTRIBUTE_PASSWORD => 'NewPassword123!',
                'password_confirmation'  => 'NewPassword123!',
            ])
            ->assertRedirect(route('account.password'));

        expect(Hash::check('NewPassword123!', $user->refresh()->password))->toBeTrue();
    });

    test('password update validates input', function (array $data, string $field): void {
        $user = User::factory()->create([
            User::ATTRIBUTE_PASSWORD => 'OldPassword123!',
        ]);

        actingAs($user)
            ->patch(route('account.password.update'), $data)
            ->assertSessionHasErrors($field);
    })->with([
        'current password must be correct' => [
            [
                'current_password'       => 'WrongPassword123!',
                User::ATTRIBUTE_PASSWORD => 'NewPassword123!',
                'password_confirmation'  => 'NewPassword123!',
            ],
            'current_password',
        ],
        'current password is required' => [
            [
                User::ATTRIBUTE_PASSWORD => 'NewPassword123!',
                'password_confirmation'  => 'NewPassword123!',
            ],
            'current_password',
        ],
        'new password is required' => [
            ['current_password' => 'OldPassword123!'],
            User::ATTRIBUTE_PASSWORD,
        ],
        'new password must be confirmed' => [
            [
                'current_password'       => 'OldPassword123!',
                User::ATTRIBUTE_PASSWORD => 'NewPassword123!',
                'password_confirmation'  => 'DifferentPassword123!',
            ],
            User::ATTRIBUTE_PASSWORD,
        ],
        'new password must differ from current password' => [
            [
                'current_password'       => 'OldPassword123!',
                User::ATTRIBUTE_PASSWORD => 'OldPassword123!',
                'password_confirmation'  => 'OldPassword123!',
            ],
            User::ATTRIBUTE_PASSWORD,
        ],
    ]);

    test('guests cannot update password', function (): void {
        patch(route('account.password.update'), [
            'current_password'       => 'OldPassword123!',
            User::ATTRIBUTE_PASSWORD => 'NewPassword123!',
            'password_confirmation'  => 'NewPassword123!',
        ])->assertRedirect(route('login'));
    });
});

describe('account deletion', function (): void {

    test('users can delete their account with correct password', function (): void {
        $user = User::factory()->create([
            User::ATTRIBUTE_PASSWORD => 'Password123!',
        ]);

        actingAs($user)
            ->delete(route('account.destroy'), [
                User::ATTRIBUTE_PASSWORD => 'Password123!',
            ])
            ->assertRedirect('/');

        assertGuest();
        assertDatabaseMissing(User::TABLE, ['id' => $user->id]);
    });

    test('account deletion validates password', function (array $data): void {
        $user = User::factory()->create([
            User::ATTRIBUTE_PASSWORD => 'Password123!',
        ]);

        actingAs($user)
            ->delete(route('account.destroy'), $data)
            ->assertSessionHasErrors(User::ATTRIBUTE_PASSWORD);

        assertDatabaseHas(User::TABLE, ['id' => $user->id]);
    })->with([
        'password is required'         => [[]],
        'password must be correct'     => [[User::ATTRIBUTE_PASSWORD => 'WrongPassword123!']],
    ]);

    test('guests cannot delete account', function (): void {
        delete(route('account.destroy'), [
            User::ATTRIBUTE_PASSWORD => 'Password123!',
        ])->assertRedirect(route('login'));
    });
});

describe('account pages', function (): void {

    test('users can view the page', function (string $route, string $component): void {
        actingAs(User::factory()->create())
            ->get(route($route))
            ->assertOk()
            ->assertInertia(fn (Assert $page): Assert => $page->component($component));
    })->with([
        'two factor authentication' => ['account.two-factor', 'account/2fa'],
        'appearance'                => ['account.appearance', 'account/appearance'],
    ]);

    test('guests cannot view the page', function (string $route): void {
        get(route($route))
            ->assertRedirect(route('login'));
    })->with([
        'two factor authentication' => ['account.two-factor'],
        'appearance'                => ['account.appearance'],
    ]);
});
