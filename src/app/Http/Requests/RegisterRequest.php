<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

final class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            User::ATTRIBUTE_NAME     => [
                'required',
                'string',
                'max:255',
            ],
            User::ATTRIBUTE_EMAIL    => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::TABLE),
            ],
            User::ATTRIBUTE_PASSWORD => [
                'required',
                'string',
                Password::defaults(),
            ],
        ];
    }
}
