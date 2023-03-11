<?php

namespace Database\Factories;

use App\Models\Gps;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class GpsFactory extends Factory
{
    protected $model = Gps::class;

    public function definition()
    {
        // Generate coordinates for MASCARA using https://www.gps-latitude-longitude.com/longitude-latitude-to-address
        $longitude = $this->faker->unique()->randomFloat(6, 35.0, 35.5);
        $latitude = $this->faker->unique()->randomFloat(6, 0.2, 0.7);

        return [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'idPatient' => function () {
                return Patient::pluck('idPatient')->random();
            },
            'dateCreate' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
