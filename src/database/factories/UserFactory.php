<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

final class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'                      => fake()->name(),
            'email'                     => fake()->unique()->safeEmail(),
            'email_verified_at'         => now(),
            'password'                  => 'password',
            'remember_token'            => Str::random(10),
            'two_factor_secret'         => Str::random(10),
            'two_factor_recovery_codes' => Str::random(10),
            'two_factor_confirmed_at'   => now(),
        ];
    }

    public function unverified(): UserFactory
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function withoutTwoFactor(): UserFactory
    {
        return $this->state(fn(array $attributes) => [
            'two_factor_secret'         => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at'   => null,
        ]);
    }
}
