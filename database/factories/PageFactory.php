<?php

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Page>
 */
class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(['Company', 'Famous', 'Specialty']),
            'name' => fake()->randomElement([fake()->company(), fake()->name()]),
            'bio' => fake()->realText(),
            'email' => fake()->unique()->companyEmail()
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterMaking(function (Page $page) {
            // ...
        })->afterCreating(function (Page $page) {

            $page->copyMedia('C:\Users\yesma\OneDrive\Desktop\20230710_063143.jpg')
                ->toMediaCollection('cover_image');

            $page->copyMedia('C:\Users\yesma\OneDrive\Desktop\-5983087055229533098_121.jpg')
                ->toMediaCollection('main_image');
        });
    }
}
