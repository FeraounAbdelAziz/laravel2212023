<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Patient;

class TemperatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $patients = Patient::all();
        $prevDateCreate = null;

        foreach ($patients as $patient) {
            // Generate 10 temperature records with a 5-second interval between each record
            $dateCreate = $prevDateCreate ? $prevDateCreate->copy()->addMinutes(10) : now()->subDays(30)->setHour(0)->setMinute(0)->setSecond(0);

            for ($i = 0; $i < 10; $i++) {
                $tempValue = mt_rand(350, 400) / 10;

                DB::table('temperature')->insert([
                    'tempValue' => $tempValue,
                    'idPatient' => $patient->idPatient,
                    'dateCreate' => $dateCreate,
                ]);

                $dateCreate = $dateCreate->addSeconds(5);
            }

            $prevDateCreate = $dateCreate;
        }

        // Generate 10 temperature records with random placement between other patients
        $otherPatients = $patients->shuffle()->take(9);

        foreach ($otherPatients as $otherPatient) {
            $dateCreate = now()->subDays(mt_rand(1, 30))->setHour(mt_rand(0, 23))->setMinute(mt_rand(0, 59))->setSecond(mt_rand(0, 59));

            for ($i = 0; $i < 10; $i++) {
                $tempValue = mt_rand(350, 400) / 10;

                DB::table('temperature')->insert([
                    'tempValue' => $tempValue,
                    'idPatient' => $otherPatient->idPatient,
                    'dateCreate' => $dateCreate,
                ]);

                $dateCreate = $dateCreate->addSeconds(5);
            }
        }
    }
}
