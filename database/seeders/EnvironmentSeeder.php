<?php

namespace Database\Seeders;

use App\Models\Environment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\Patient;

class EnvironmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $patients = Patient::all();

        foreach ($patients as $patient) {
            $currentMonth = Carbon::now()->startOfMonth();
            $nextMonth = Carbon::now()->addMonth(10)->startOfMonth();

            $environments= [];
            for ($i = 0; $i < 5000; $i++) {
                $dateCreate = $currentMonth->copy()->addMinutes($i * 30);
                $environments[] = [
                    'idPatient' => $patient->idPatient,
                    'tempEnvValue' => rand(35, 42),
                    'dateCreate' => $dateCreate->toDateTimeString(),
                ];
            }

            for ($i = 0; $i < 5000; $i++) {
                $dateCreate = $nextMonth->copy()->addMinutes($i * 30);
                $environments[] = [
                    'idPatient' => $patient->idPatient,
                    'tempEnvValue' => rand(35, 42),
                    'dateCreate' => $dateCreate->toDateTimeString(),
                ];
            }

            Environment::insert($environments);
        }
    }
}
