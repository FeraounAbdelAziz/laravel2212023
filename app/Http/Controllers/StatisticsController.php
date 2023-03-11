<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Assignment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{

    public function statistics()
    {
        $data = [
            'TotalDevices' => Device::all()->count(),
            'AvailableDevices' => DB::table('Device')
            ->where('assignmentStatus', 0)
            ->get()->count(),
            'AssignedDevices' => Assignment::all()->count(),
            'TotalPatients' => Patient::all()->count(),
            'PatientsHaveDevice' => DB::table('Patient')
            ->where('assignmentStatus', 1)
            ->get()->count(),
            'PatientswithNoDevice' => DB::table('Patient')
            ->where('assignmentStatus', 0)
            ->get()->count(),
            'TotalUsers' => Doctor::all()->count(),
            // 'LostDevices' => Doctor::all()->count(),
        ];
        return $data;
    }

}
