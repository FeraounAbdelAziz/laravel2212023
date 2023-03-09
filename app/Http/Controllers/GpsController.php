<?php

namespace App\Http\Controllers;

use App\Models\Gps;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class GpsController extends Controller
{
    public function getLastGpsPatient(string $idPatient)
    {
        return Gps::where('idPatient', $idPatient)
            ->orderBy('dateCreate', 'desc')
            ->select('gps.longitude', 'gps.latitude', 'gps.dateCreate')
            ->get();
    }


    public function getGpsForDateRange(Request $request, $idPatient)
    {
        $GpsS = Gps::where('idPatient', $idPatient)
            ->whereBetween('dateCreate', [$request->startDate, $request->endDate . ' 23:59:59'])
            ->orderBy('dateCreate')
            ->select('gps.longitude', 'gps.latitude', 'gps.dateCreate')
            ->get();

        return response()->json([
            'gps' => $GpsS
        ]);
    }


}
