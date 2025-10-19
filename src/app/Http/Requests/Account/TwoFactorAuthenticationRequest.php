<?php

declare(strict_types=1);

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;
use Laravel\Fortify\Features;
use Laravel\Fortify\InteractsWithTwoFactorState;

final class TwoFactorAuthenticationRequest extends FormRequest
{
    use InteractsWithTwoFactorState;

    public function authorize(): bool
    {
        return Features::enabled(Features::twoFactorAuthentication());
    }

    public function rules(): array
    {
        return [];
    }
}
