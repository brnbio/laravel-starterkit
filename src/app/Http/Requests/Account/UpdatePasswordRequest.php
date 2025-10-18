<?php

declare(strict_types=1);

namespace App\Http\Requests\Account;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

final class UpdatePasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'current_password'       => [
                'required',
                'current_password',
            ],
            User::ATTRIBUTE_PASSWORD => [
                'required',
                'confirmed',
                'different:current_password',
                Password::defaults(),
            ],
        ];
    }
}
