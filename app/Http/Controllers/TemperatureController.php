<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Temperature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TemperatureController extends Controller
{
    public function patientTemp(string $idPatient)
    {
        return DB::table('temperature')
            ->where('temperature.idPatient', '=', $idPatient)
            ->get();
    }
    public function getTemperaturesForDateRange(Request $request, $idPatient)
    {

        $temperatures = Temperature::where('idPatient', $idPatient)
            ->whereBetween('dateCreate', [$request->startDate, $request->endDate . ' 23:59:59'])
            ->orderBy('dateCreate')
            ->get();

        return response()->json($temperatures);
    }
}
