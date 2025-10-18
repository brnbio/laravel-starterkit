<?php

declare(strict_types=1);

namespace App\Http\Requests\Account;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

final class DeleteUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            User::ATTRIBUTE_PASSWORD => [
                'required',
                'current_password:',
            ],
        ];
    }
}
