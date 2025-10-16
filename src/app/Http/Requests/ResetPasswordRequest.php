<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $token
 */
final class ResetPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            User::ATTRIBUTE_EMAIL       => [
                'required',
                'email',
            ],
            User::ATTRIBUTE_PASSWORD    => [
                'required',
                'between:8,48',
                'confirmed',
            ],
            User::ATTRIBUTE_RESET_TOKEN => [
                'required',
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            User::ATTRIBUTE_RESET_TOKEN => $this->token,
        ]);
    }
}
