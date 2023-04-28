<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class HeartBeatFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = HeartBeat::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $idPatient = Patient::factory()->create()->idPatient;

        $heartBeats = [];

        for ($i = 0; $i < 10; $i++) {
            $heartBeats[] = [
                'hbValue' => $this->faker->randomFloat(2, 60, 100),
                'dateCreate' => $this->faker->dateTimeBetween("-1 week", "now")
            ];
        }

        return [
            'idPatient' => $idPatient,
            'hbValue' => serialize($heartBeats)
        ];
    }
}
