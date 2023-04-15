<?php

namespace App\Http\Controllers;

use App\Models\Temperature;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Summary of TemperatureController
 */
class TemperatureController extends Controller
{
    public function getTempPatient(Request $request, string $idPatient)
    {
        $dateTimeString = date('Y-m-d H:i:s', strtotime($request->dateConsult)); // this convert the YYYY-MM-DD to YYYY-MM-DD HH-MM-SS

        $temperatures = Temperature::where('idPatient', '=', $idPatient)
            ->whereDate('dateCreate', $dateTimeString)
            ->select("temperature.tempValue", "temperature.dateCreate")
            ->get();

        $groupedTemperatures = $temperatures->groupBy(function ($temperature) {
            return Carbon::parse($temperature->dateCreate)->format('F j, Y');
        });

        $result = [];
        foreach ($groupedTemperatures as $month => $temperatures) {
            $tempValues = $temperatures->pluck('tempValue');
            $dateCreates = $temperatures->pluck('dateCreate');

            $result[] = [
                'month' => $month,
                'temperatures' => [
                    'tempValue' => $tempValues,
                    'dateCreate' => $dateCreates
                ]
            ];
        }

        return response()->json([
            'temperatures' => $result
        ]);
    }

    public function lastTemperatureRecord(string $idPatient)
    {
        $lastTemperature = Temperature::where('idPatient', $idPatient)
            ->orderBy('dateCreate', 'desc')
            ->take(10)
            ->select("temperature.tempValue", "temperature.dateCreate")
            ->get();

        $filteredTemperatures = collect([]);
        foreach ($lastTemperature as $key => $temperature) {
            if ($key === 0) {
                // add first temperature to the filtered collection
                $filteredTemperatures->push($temperature);
            } else {
                $prevTemperature = $lastTemperature[$key - 1];

                $date1 = Carbon::parse($temperature->dateCreate);
                $date2 = Carbon::parse($prevTemperature->dateCreate);

                $diffInSeconds = $date2->diffInSeconds($date1);
                if ($diffInSeconds >= 5) {
                    $filteredTemperatures->push($temperature);
                }
            }
        }

        $tempValue = $lastTemperature->map(function ($temperature) {
            return $temperature->tempValue;
        })->sort()->values();

        $dateCreate = $lastTemperature->map(function ($temperature) {
            return $temperature->dateCreate;
        })->sort()->values();
        return response()->json([
            'tempValue' => $tempValue,
            'dateCreate' => $dateCreate,
        ]);
    }

    //THIS FILL ALL THE DATES BTW THE START_DATE TO END_DATE !!
    // public function getTemperaturesForDateRange(Request $request, $idPatient)
    // {
    //     $temperatures = Temperature::where('idPatient', $idPatient)
    //         ->whereBetween('dateCreate', [$request->startDate, $request->endDate . ' 23:59:59'])
    //         ->orderBy('dateCreate')
    //         ->select("temperature.tempValue", "temperature.dateCreate")
    //         ->get();

    //     $filledTemperatures = collect([]);

    //     if ($temperatures->count() > 0) {
    //         $firstReadingDate = $temperatures->first()->dateCreate;
    //         $lastReadingDate = $temperatures->last()->dateCreate;

    //         // Fill in the missing temperature readings with a 30-minute interval
    //         $interval = CarbonInterval::minutes(30);
    //         $currentDate = Carbon::parse($firstReadingDate);
    //         $endDate = Carbon::parse($lastReadingDate);

    //         while ($currentDate <= $endDate) {
    //             $matchingReading = $temperatures->firstWhere('dateCreate', $currentDate);
    //             if ($matchingReading) {
    //                 $filledTemperatures->push([
    //                     'tempValue' => $matchingReading->tempValue,
    //                     'dateCreate' => $currentDate->toDateTimeString(),
    //                 ]);
    //             } else {
    //                 $filledTemperatures->push([
    //                     'tempValue' => null,
    //                     'dateCreate' => $currentDate->toDateTimeString(),
    //                 ]);
    //             }
    //             $currentDate->add($interval);
    //         }
    //     }

    //     $tempValue = $filledTemperatures->pluck('tempValue')->values();
    //     $dateCreate = $filledTemperatures->pluck('dateCreate')->values();

    //     return response([
    //         'temperatures' => [
    //             'tempValue' => $tempValue,
    //             'dateCreate' => $dateCreate
    //         ]
    //     ], 200);
    // }

    public function getTemperaturesForDateRange(Request $request, $idPatient)
    {
        $temperatures = Temperature::where('idPatient', $idPatient)
            ->whereBetween('dateCreate', [$request->startDate, $request->endDate . ' 23:59:59'])
            ->orderBy('dateCreate')
            ->select("temperature.tempValue", "temperature.dateCreate")
            ->get();

        $groupedTemperatures = $temperatures->groupBy(function ($temperature) {
            return Carbon::parse($temperature->dateCreate)->format('F Y');
        });

        $result = [];
        foreach ($groupedTemperatures as $month => $temperatures) {
            $tempValues = $temperatures->pluck('tempValue');
            $dateCreates = $temperatures->pluck('dateCreate');

            $result[] = [
                'month' => $month,
                'temperatures' => [
                    'tempValue' => $tempValues,
                    'dateCreate' => $dateCreates
                ]
            ];
        }

        return response([
            'temperatures' => $result
        ], 200);
    }

//DISPLAY ALL TEMPERATRE IN CASE THE LAST ONE DIDNT WORK !
// public function getTemperaturesForDateRange(Request $request, $idPatient)
// {
//     $temperatures = Temperature::where('idPatient', $idPatient)
//         ->whereBetween('dateCreate', [$request->startDate, $request->endDate . ' 23:59:59'])
//         ->orderBy('dateCreate')
//         ->select("temperature.tempValue", "temperature.dateCreate")
//         ->get();
//     $tempValue = $temperatures->map(function ($temperature) {
//         return $temperature->tempValue;
//     })->values();

//     $dateCreate = $temperatures->map(function ($temperature) {
//         return $temperature->dateCreate;
//     })->values();
//     return response([
//         'temperatures' => [
//             'tempValue' => $tempValue,
//             'dateCreate' => $dateCreate
//         ]
//     ], 200);
// }
}
