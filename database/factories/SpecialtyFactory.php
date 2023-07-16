<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Specialty>
 */
class SpecialtyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $specialty = config('specialty.specialty');

        $section = config('specialty.section');

        $framework = config('specialty.framework');

        $language = config('specialty.language');

        return [
            'specialty' => implode(",", fake()->randomElements($specialty)),
            'section' => implode(",", fake()->randomElements($section, rand(1, 32))),
            'framework' => implode(",", fake()->randomElements($framework, rand(1, 11))),
            'language' => implode(",", fake()->randomElements($language, rand(1, 13)))
        ];
    }
}
