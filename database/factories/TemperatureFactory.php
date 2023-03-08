<?php
namespace Database\Factories;

use App\Models\Patient;
use App\Models\Temperature;
use Illuminate\Database\Eloquent\Factories\Factory;

class TemperatureFactory extends Factory
{
    protected $model = Temperature::class;

    public function definition()
    {
        $idPatient = Patient::factory()->create()->idPatient;

        $temperatures = [];

        for ($i = 0; $i < 10; $i++) {
            $temperatures[] = [
                'tempValue' => $this->faker->randomFloat(2, 35, 40),
                'dateCreate' => $this->faker->dateTimeBetween("-1 week", "now")
            ];
        }

        return [
            'idPatient' => $idPatient,
            'tempValue' => serialize($temperatures)
        ];
    }
}
