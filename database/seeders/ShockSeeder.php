<?php
namespace Database\Seeders;

use App\Models\Patient;
use App\Models\Shock;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ShockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {$faker = Factory::create();
        $patients = Patient::all();

        foreach ($patients as $patient) {
            $currentMonth = Carbon::now()->startOfMonth();
            $nextMonth = Carbon::now()->addMonth(10)->startOfMonth();

            $shocks = [];
            for ($i = 0; $i < 5000; $i++) {
                $dateCreate = $currentMonth->copy()->addMinutes($i * 30);
                $shockValue = $faker->randomElement([0, 1], [99, 1]);
                $shocks[] = [
                    'idPatient' => $patient->idPatient,
                    'shockValue' => $shockValue,
                    'dateCreate' => $dateCreate->toDateTimeString(),
                ];
            }

            for ($i = 0; $i < 5000; $i++) {
                $dateCreate = $nextMonth->copy()->addMinutes($i * 30);
                $shockValue = $faker->randomElement([0, 1], [99, 1]);
                $shocks[] = [
                    'idPatient' => $patient->idPatient,
                    'shockValue' => $shockValue,
                    'dateCreate' => $dateCreate->toDateTimeString(),
                ];
            }

            Shock::insert($shocks);
        }
    }
}
