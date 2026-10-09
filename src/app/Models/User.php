<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Fortify\TwoFactorAuthenticatable;

/**
 * @property-read int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string $remember_token
 * @property string $two_factor_secret
 * @property string $two_factor_recovery_codes
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 */
#[Table(self::TABLE)]
#[Fillable([self::ATTRIBUTE_NAME, self::ATTRIBUTE_EMAIL, self::ATTRIBUTE_PASSWORD, self::ATTRIBUTE_REMEMBER_TOKEN])]
#[Hidden([self::ATTRIBUTE_PASSWORD, self::ATTRIBUTE_REMEMBER_TOKEN])]
#[UseFactory(UserFactory::class)]
final class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
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

    protected $casts = [
        self::ATTRIBUTE_PASSWORD => 'hashed',
    ];
}
