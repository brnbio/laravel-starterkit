<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Response;

final class AuthController
{
    public function showLoginForm(): Response
    {
        return inertia('auth/login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        if (!$this->attemptLogin($request->validated(), $request->filled('remember'))) {
            return $this->invalidLogin();
        }

        return $this->sendLoginResponse($request);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('login');
    }

    public function showRegisterForm(): Response
    {
        return inertia('auth/register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = User::query()->create($request->validated());
        Auth::guard()->login($user);
        $request->session()->regenerate();
        flash()->success('Ihr Account wurde erfolgreich erstellt. Herzlich willkommen!');

        return to_route('account');
    }

    public function showForgotPasswordForm(): Response
    {
        return inertia('auth/forgot-password');
    }

    public function sendResetLinkEmail(ForgotPasswordRequest $request): RedirectResponse
    {
        $response = Password::broker()->sendResetLink($request->validated());
        if ($response !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                User::ATTRIBUTE_EMAIL => [__($response)],
            ]);
        }
        flash()->success('Sie erhalten per E-Mail einen Link, mit dem Sie ein neues Passwort vergeben können.');

        return to_route('password.request');
    }

    public function showResetPasswordForm(Request $request): Response
    {
        return inertia('auth/reset-password', [
            'email' => $request->email,
            'token' => $request->route('token'),
        ]);
    }

    public function reset(ResetPasswordRequest $request): RedirectResponse
    {
        $response = $this->resetPassword($request);
        if ($response !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                User::ATTRIBUTE_EMAIL => [__($response)],
            ]);
        }
        flash()->success('Passwort erfolgreich zurückgesetzt.');

        return to_route('account');
    }

    protected function attemptLogin(array $credentials, bool $remember = false): bool
    {
        return Auth::guard()->attempt($credentials, $remember);
    }

    protected function sendLoginResponse(LoginRequest $request): RedirectResponse
    {
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    protected function invalidLogin(): RedirectResponse
    {
        return back()->withErrors([
            User::ATTRIBUTE_EMAIL => 'Anmeldung ungültig.',
        ]);
    }

    protected function resetPassword(ResetPasswordRequest $request): string
    {
        /** @var PasswordBroker $broker */
        $broker = Password::broker();

        return $broker->reset(
            $request->validated(),
            function (User $user, string $password) {
                $user->update([
                    User::ATTRIBUTE_PASSWORD       => $password,
                    User::ATTRIBUTE_REMEMBER_TOKEN => Str::random(100),
                ]);
                event(new PasswordReset($user));
                Auth::guard()->login($user);
            },
        );
    }
}
