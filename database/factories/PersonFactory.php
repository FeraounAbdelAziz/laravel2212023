<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class PersonFactory extends Factory
{

    public function definition(): array
    {
        return [
            'firstName' => fake()->name(),
            'lastName' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'telNum' => fake()->unique()->numerify('05########'),
            'birthdate' => fake()->unique()->date(),
            'adress'=> fake()->address(),
        ];
    }

}
