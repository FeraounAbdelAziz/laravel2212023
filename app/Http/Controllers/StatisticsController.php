<?php

namespace App\Http\Controllers;

use App\Models\Affectation;
use App\Models\Patient;
use Illuminate\Support\Facades\DB;
use App\Models\Appareil;

class StatisticsController extends Controller
{

    public function Statistics()
    {
         $data = [
        'TotalDevices' => Appareil::all()->count(),
        'AvailableDevices' => DB::table('Appareil')
            ->where('etatAffectation', 0)
            ->get()->count(),
        'AssignedDevices' => Affectation::all()->count(),
        'TotalPatients' => Patient::all()->count(),
        'PatientsHaveDevice' => DB::table('Patient')
            ->where('etatAffectation', 1)
            ->get()->count(),
        'PatientswithNoDevice' => DB::table('Patient')
            ->where('etatAffectation', 0)
            ->get()->count(),
        ];
        return $data;
    }

}
