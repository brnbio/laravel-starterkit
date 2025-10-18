<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Account\DeleteUserRequest;
use App\Http\Requests\Account\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

final class AccountController
{
    public function edit(): Response
    {
        return inertia('account/edit');
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());
        if ($request->user()->isDirty(User::ATTRIBUTE_EMAIL)) {
            $request->user()->email_verified_at = null;
        }
        $request->user()->save();
        flash()->success('Ihre Daten wurden erfolgreich aktualisiert.');

        return to_route('account.edit');
    }

    public function destroy(DeleteUserRequest $request): RedirectResponse
    {
        $user = $request->user();
        auth()->logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        flash()->success('Ihr Konto wurde erfolgreich gelöscht.');

        return redirect('/');
    }

    public function editPassword(): Response
    {
        return inertia('account/show');
    }

    public function updatePassword(): Response
    {
        return inertia('account/show');
    }

    public function twoFactorAuthentication(): Response
    {
        return inertia('account/show');
    }

    public function appearance(): Response
    {
        return inertia('account/show');
    }
}
