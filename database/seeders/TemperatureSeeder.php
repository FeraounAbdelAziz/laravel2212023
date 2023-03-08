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
    }
}
