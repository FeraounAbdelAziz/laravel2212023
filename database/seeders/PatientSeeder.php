<?php
namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch the first doctor (or any other logic to select a doctor)
        $doctorId = DB::table('doctor')->first()->idDoctor;

        // Create 20 patients and assign them to the doctor
        Patient::factory()->count(1)->create([
            'idDoctor' => $doctorId,  // Assign the doctor ID to the patient
        ]);
    }
}
