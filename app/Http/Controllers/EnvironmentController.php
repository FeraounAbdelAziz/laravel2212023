<?php

namespace App\Http\Controllers;

use App\Models\Environment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EnvironmentController extends Controller
{
    public function getEnvironmentPatient(Request $request, string $idPatient)
    {
        $Environment = Environment::where('idPatient', '=', $idPatient)
            ->whereRaw('DATE(dateCreate) = ?', $request->dateConsult) // this convert the YYYY-MM-DD to YYYY-MM-DD HH-MM-SS
            ->take(10)
            ->select("environment.tempEnvValue", "environment.dateCreate")
            ->get();
        $filteredTemperatures = collect([]);

        foreach ($Environment as $key => $temperature) {
            if ($key === 0) {
                // add first temperature to the filtered collection
                $filteredTemperatures->push($temperature);
            } else {
                $prevTemperature = $Environment[$key - 1];

                $date1 = Carbon::parse($temperature->dateCreate);
                $date2 = Carbon::parse($prevTemperature->dateCreate);

                $diffInSeconds = $date2->diffInSeconds($date1);
                if ($diffInSeconds >= 5) {
                    $filteredTemperatures->push($temperature);
                }
            }
        }

        $tempEnvValue = $Environment->map(function ($environment) {
            return $environment->tempEnvValue;
        })->sort()->values();

        $dateCreate = $Environment->map(function ($environment) {
            return $environment->dateCreate;
        })->sort()->values();
        return response()->json([
            'tempEnvValue' => $tempEnvValue,
            'dateCreate' => $dateCreate,
        ]);

    }

    public function lastEnvironmentRecord(string $idPatient)
    {
        $lastTemperature = Environment::where('idPatient', $idPatient)
            ->orderBy('dateCreate', 'desc')
            ->take(10)
            ->select("environment.tempEnvValue", "environment.dateCreate")
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

        $tempEnvValue = $lastTemperature->map(function ($temperature) {
            return $temperature->tempEnvValue;
        })->sort()->values();

        $dateCreate = $lastTemperature->map(function ($temperature) {
            return $temperature->dateCreate;
        })->sort()->values();
        return response()->json([
            'tempEnvValue' => $tempEnvValue,
            'dateCreate' => $dateCreate,
        ]);
    }

    //THIS FILL ALL THE DATES BTW THE START_DATE TO END_DATE !!
    // public function getTemperaturesForDateRange(Request $request, $idPatient)
    // {
    //     $temperatures = Temperature::where('idPatient', $idPatient)
    //         ->whereBetween('dateCreate', [$request->startDate, $request->endDate . ' 23:59:59'])
    //         ->orderBy('dateCreate')
    //         ->select("temperature.tempEnvValue", "temperature.dateCreate")
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
    //                     'tempEnvValue' => $matchingReading->tempEnvValue,
    //                     'dateCreate' => $currentDate->toDateTimeString(),
    //                 ]);
    //             } else {
    //                 $filledTemperatures->push([
    //                     'tempEnvValue' => null,
    //                     'dateCreate' => $currentDate->toDateTimeString(),
    //                 ]);
    //             }
    //             $currentDate->add($interval);
    //         }
    //     }

    //     $tempEnvValue = $filledTemperatures->pluck('tempEnvValue')->values();
    //     $dateCreate = $filledTemperatures->pluck('dateCreate')->values();

    //     return response([
    //         'temperatures' => [
    //             'tempEnvValue' => $tempEnvValue,
    //             'dateCreate' => $dateCreate
    //         ]
    //     ], 200);
    // }
    public function getEnvironmentForDateRange(Request $request, $idPatient)
    {
        $temperatures = Environment::where('idPatient', $idPatient)
            ->whereBetween('dateCreate', [$request->startDate, $request->endDate . ' 23:59:59'])
            ->orderBy('dateCreate')
            ->select("environment.tempEnvValue", "environment.dateCreate")
            ->get();
        $tempEnvValue = $temperatures->map(function ($temperature) {
            return $temperature->tempEnvValue;
        })->values();

        $dateCreate = $temperatures->map(function ($temperature) {
            return $temperature->dateCreate;
        })->values();
        return response([
            'environments' => [
                'tempEnvValue' => $tempEnvValue,
                'dateCreate' => $dateCreate
            ]
        ], 200);
    }
}
