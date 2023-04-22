<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\Shock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shock>
 */
class ShockFactory extends Factory
{
    protected $model = Shock::class;

    public function definition(): array
    {
        $idPatient = Patient::factory()->create()->idPatient;
        return [
            'shockValue' => $this->faker->randomElement([0, 1], [99, 1]),
            'idPatient' => $idPatient,
            'dateCreate' => $this->faker->dateTime(),
        ];
    }
}
