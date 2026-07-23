<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),

            'email' => fake()
                ->unique()
                ->safeEmail(),

            'email_verified_at' => now(),

            'password' => static::$password
                ??= Hash::make(
                    'password'
                ),

            'remember_token' => Str::random(10),

            'role' => User::ROLE_KARYAWAN,

            'status' => User::STATUS_ACTIVE,

            'semester' => 1,

            'annual_leave_quota' => 12,
        ];
    }

    public function unverified(): static
    {
        return $this->state(
            fn (array $attributes): array => [
                'email_verified_at' => null,
            ]
        );
    }

    public function manager(): static
    {
        return $this->state(
            fn (): array => [
                'role' => User::ROLE_MANAGER,
            ]
        );
    }

    public function hr(): static
    {
        return $this->state(
            fn (): array => [
                'role' => User::ROLE_HR,
            ]
        );
    }

    public function superAdmin(): static
    {
        return $this->state(
            fn (): array => [
                'role' => User::ROLE_SUPER_ADMIN,
            ]
        );
    }
}
