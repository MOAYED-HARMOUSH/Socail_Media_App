<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Community;
use Illuminate\Support\Str;
use App\Http\Controllers\Api\CommunityController;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $genders = ['male', 'female'];
        $gender = $genders[rand(0, 1)];

        $programming_age = fake()->dateTimeBetween();
        $birth_date = Carbon::parse($programming_age)->subYears(rand(12, 20));

        return [
            'first_name' => fake()->firstName($gender),
            'last_name' => fake()->lastname(),
            'user_identifier' => fake()->unique()->userName(),
            'gender' => $gender,
            'phone_number' => fake()->unique()->phoneNumber(),
            'bio' => fake()->realText(),
            'remember_token' => Str::random(10),
            'current_location' => fake()->country(),
            'country' => fake()->country(),
            'programming_age' => $programming_age,
            'birth_date' => $birth_date,
            'email' => fake()->unique()->safeEmail(),
            'password' => fake()->password(8),
            'email_verified_at' => now()
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterMaking(function (User $user) {
            // ...
        })->afterCreating(function (User $user) {

            $user->copyMedia('C:\Users\yesma\OneDrive\Desktop\-5983087055229533098_121.jpg')
                ->toMediaCollection('avatar');

            $token = $user->createToken('Sign up', [''], now()->addYear())->plainTextToken;

            $specialty = $user->specialty()->first();

            CommunityController::addUserToCommunity($specialty, $user);

            info($token . PHP_EOL);
        });
    }
}
