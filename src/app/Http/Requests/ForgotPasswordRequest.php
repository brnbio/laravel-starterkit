<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

final class ForgotPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            User::ATTRIBUTE_EMAIL => [
                'required',
                'email',
            ],
        ];
    }
}
