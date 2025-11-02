<?php

declare(strict_types=1);

namespace App\Models;

use App\Model;
use Database\Factories\UserFactory;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

/**
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
 */
#[UseFactory(UserFactory::class)]
final class User extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;
    use HasFactory;
    use Notifiable;
    use TwoFactorAuthenticatable;

    public const string TABLE = 'core_users';

    public const string ATTRIBUTE_NAME = 'name';
    public const string ATTRIBUTE_EMAIL = 'email';
    public const string ATTRIBUTE_PASSWORD = 'password';
    public const string ATTRIBUTE_REMEMBER_TOKEN = 'remember_token';
    public const string ATTRIBUTE_RESET_TOKEN = 'token';

    protected $fillable = [
        self::ATTRIBUTE_NAME,
        self::ATTRIBUTE_EMAIL,
        self::ATTRIBUTE_PASSWORD,
        self::ATTRIBUTE_REMEMBER_TOKEN,
    ];

    protected $hidden = [
        self::ATTRIBUTE_PASSWORD,
        self::ATTRIBUTE_REMEMBER_TOKEN,
    ];

    protected $casts = [
        self::ATTRIBUTE_PASSWORD => 'hashed',
    ];
}
