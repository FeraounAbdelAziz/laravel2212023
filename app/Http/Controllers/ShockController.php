<?php

namespace App\Http\Controllers;

use App\Models\Shock;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ShockController extends Controller
{
    public function getShockPatient(Request $request, string $idPatient)
    {
        $dateTimeString = date('Y-m-d H:i:s', strtotime($request->dateConsult)); // this convert the YYYY-MM-DD to YYYY-MM-DD HH-MM-SS

        $Shocks = Shock::where('idPatient', '=', $idPatient)
            ->whereDate('dateCreate', $dateTimeString)
            ->select("shock.shockValue", "shock.dateCreate")
            ->get();

        $groupedShocks = $Shocks->groupBy(function ($shock) {
            return Carbon::parse($shock->dateCreate)->format('F j, Y');
        });

        $result = [];
        foreach ($groupedShocks as $month => $Shocks) {
            $shockValues = $Shocks->pluck('shockValue');
            $dateCreates = $Shocks->pluck('dateCreate');

            $result[] = [
                'month' => $month,
                'shocks' => [
                    'shockValue' => $shockValues,
                    'dateCreate' => $dateCreates
                ]
            ];
        }

        return response()->json([
            'shocks' => $result
        ]);
    }
    public function getShockForDateRange(Request $request ,  $idPatient) {
        $Shocks = Shock::where('idPatient', $idPatient)
            ->whereBetween('dateCreate', [$request->startDate, $request->endDate . ' 23:59:59'])
            ->orderBy('dateCreate')
            ->select("shock.shockValue", "shock.dateCreate")
            ->get();

        $Shocks = $Shocks->groupBy(function ($shock) {
            return Carbon::parse($shock->dateCreate)->format('F Y');
        });

        $result = [];
        foreach ($Shocks as $month => $Shocks) {
            $shockValues = $Shocks->pluck('shockValue');
            $dateCreates = $Shocks->pluck('dateCreate');

            $result[] = [
                'month' => $month,
                'shocks' => [
                    'shockValue' => $shockValues,
                    'dateCreate' => $dateCreates
                ]
            ];
        }

        return response([
            'shocks' => $result
        ], 200);
    }
}
