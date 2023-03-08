<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    protected $model = Patient::class;

    public function definition()
    {
        return [
            'idPerson' => function () {
                return Person::factory()->create()->idPerson;
            },
            'assignmentStatus' => $this->faker->boolean,
        ];
    }
}
