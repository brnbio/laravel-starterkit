<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Enum\Prefix;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

final class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            User::ATTRIBUTE_PREFIX     => [
                'required',
                Rule::enum(Prefix::class),
            ],
            User::ATTRIBUTE_FIRST_NAME => [
                'required',
                'string',
                'max:255',
            ],
            User::ATTRIBUTE_LAST_NAME  => [
                'required',
                'string',
                'max:255',
            ],
            User::ATTRIBUTE_PHONE      => [
                'nullable',
                'string',
                'max:50',
            ],
            User::ATTRIBUTE_EMAIL      => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:' . User::TABLE . ',' . User::ATTRIBUTE_EMAIL,
            ],
            User::ATTRIBUTE_PASSWORD   => [
                'required',
                'string',
                'confirmed',
                Password::defaults(),
            ],
        ];
    }
}
