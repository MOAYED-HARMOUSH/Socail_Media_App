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

        return [
           // 'name' => fake()->name(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastname(),
            'current_location' => fake()->name(),
            'programming_age'=>fake()->date(),
            'birth_date'=>fake()->date(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'gender'=>'male',

            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];}

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
    // public function configure(): static
    // {
    //     return $this->afterMaking(function (User $user) {
    //         // ...
    //     })->afterCreating(function (User $user) {

    //         $user->copyMedia('C:\Users\Admin\Desktop\photo_2023-07-15_11-10-12.jpg')
    //             ->toMediaCollection('avatar');

    //         $token = $user->createToken('Sign up', [''], now()->addYear())->plainTextToken;

    //         $specialty = $user->specialty()->first();

    //         CommunityController::addUserToCommunity($specialty, $user);

    //         echo $token . PHP_EOL;
    //     });
    // }
}
