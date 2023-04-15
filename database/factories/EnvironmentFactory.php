<?php
namespace Database\Factories;

use App\Models\Patient;
use App\Models\Environment;
use Illuminate\Database\Eloquent\Factories\Factory;


class EnvironmentFactory extends Factory
{
    protected $model = Environment::class;

    public function definition()
    {
        $idPatient = Patient::factory()->create()->idPatient;

        $environments = [];

        for ($i = 0; $i < 10; $i++) {
            $environments [] = [
                'tempEnvValue' => $this->faker->randomFloat(2, 20,25),
                'dateCreate' => $this->faker->dateTimeBetween("-1 week", "now")
            ];
        }

        return [
            'idPatient' => $idPatient,
            'tempEnvValue' => serialize($environments)
        ];
    }
}
