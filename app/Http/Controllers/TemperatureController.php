<?php

namespace App\Http\Controllers;

use App\Models\Temperature;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TemperatureController extends Controller
{
    public function getTempPatient(Request $request, string $idPatient)
    {
        $Temperature = Temperature::where('idPatient', '=', $idPatient)
            ->whereRaw('DATE(dateCreate) = ?', $request->dateConsult) // this convert the YYYY-MM-DD to YYYY-MM-DD HH-MM-SS
            ->take(10)
            ->select("temperature.tempValue", "temperature.dateCreate")
            ->get();
        $filteredTemperatures = collect([]);

        foreach ($Temperature as $key => $temperature) {
            if ($key === 0) {
                // add first temperature to the filtered collection
                $filteredTemperatures->push($temperature);
            } else {
                $prevTemperature = $Temperature[$key - 1];

                $date1 = Carbon::parse($temperature->dateCreate);
                $date2 = Carbon::parse($prevTemperature->dateCreate);

                $diffInSeconds = $date2->diffInSeconds($date1);
                if ($diffInSeconds >= 5) {
                    $filteredTemperatures->push($temperature);
                }
            }
        }

        $tempValue = $Temperature->map(function ($temperature) {
            return $temperature->tempValue;
        })->sort()->values();

        $dateCreate = $Temperature->map(function ($temperature) {
            return $temperature->dateCreate;
        })->sort()->values();
        return response()->json([
            'tempValue' => $tempValue,
            'dateCreate' => $dateCreate,
        ]);

    }




    public function getTemperaturesForDateRange(Request $request, $idPatient)
    {
        $temperatures = Temperature::where('idPatient', $idPatient)
            ->whereBetween('dateCreate', [$request->startDate, $request->endDate . ' 23:59:59'])
            ->orderBy('dateCreate')
            ->select("temperature.tempValue", "temperature.dateCreate")
            ->get();

        $groups = [];
        $groupIndex = 0;
        $lastTemperature = null;
        foreach ($temperatures as $temperature) {
            if (
                $lastTemperature !== null &&
                (abs(strtotime($temperature->dateCreate) - strtotime($lastTemperature->dateCreate)) > 10) // if the sub btw dateCreate is greater then 10 do ++
            ) {
                $groupIndex++;
            }

            if (!isset($groups[$groupIndex])) {
                $groups[$groupIndex] = [];
            }

            $groups[$groupIndex][] = [
                'tempValue' => $temperature->tempValue,
                'dateCreate' => $temperature->dateCreate
            ];

            $lastTemperature = $temperature;
        }

        $result = [];
        foreach ($groups as $group) {
            $subgroups = array_chunk($group, 10);
            foreach ($subgroups as $subgroup) {
                $result[] = $subgroup;
            }
        }

        $finalResult = [];
        foreach ($result as $index => $group) {
            $temperatures = [];
            $dates = [];
            foreach ($group as $record) {
                $temperatures[] = $record['tempValue'];
                $dates[] = $record['dateCreate'];
            }
            $finalResult['temperature' . ($index + 1)] = [
                'tempValue' => $temperatures,
                'dateCreate' => $dates,
            ];
        }
        return response()->json([
            'temperatures' => array_values($finalResult)
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
}
