<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Inertia\Response;

final class AccountController
{
    public function __invoke(): Response
    {
        return inertia('account/index');
    }
}
