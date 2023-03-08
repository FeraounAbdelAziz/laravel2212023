<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DoctorFactory extends Factory
{
    protected $model = Doctor::class;

    public function definition()
    {
        $person = Person::factory()->create();

        return [
            'email' => $this->faker->unique()->safeEmail,
            'password' => Str::random(10),
            'isVerified' => $this->faker->boolean(80), // 80% chance of being verified
            'idPerson' => $person->idPerson,
        ];
    }
}
