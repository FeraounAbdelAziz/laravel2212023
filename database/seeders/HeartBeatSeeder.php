<?php

namespace Database\Seeders;

use App\Models\HeartBeat;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class HeartBeatSeeder extends Seeder
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

            $heartBeats = [];
            for ($i = 0; $i < 5000; $i++) {
                $dateCreate = $currentMonth->copy()->addMinutes($i * 30);
                $heartBeats[] = [
                    'idPatient' => $patient->idPatient,
                    'hbValue' => rand(60, 100),
                    'dateCreate' => $dateCreate->toDateTimeString(),
                ];
            }

            for ($i = 0; $i < 5000; $i++) {
                $dateCreate = $nextMonth->copy()->addMinutes($i * 30);
                $heartBeats[] = [
                    'idPatient' => $patient->idPatient,
                    'hbValue' => rand(60, 100),
                    'dateCreate' => $dateCreate->toDateTimeString(),
                ];
            }

            HeartBeat::insert($heartBeats);
        }
    }
}
