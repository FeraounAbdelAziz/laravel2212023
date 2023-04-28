<?php

namespace App\Http\Controllers;

use App\Models\HeartBeat;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HeartBeatController extends Controller
{
    public function getHBPatient(Request $request, string $idPatient)
    {
        $dateTimeString = date('Y-m-d H:i:s', strtotime($request->dateConsult)); // this convert the YYYY-MM-DD to YYYY-MM-DD HH-MM-SS

        $heartbeats = HeartBeat::where('idPatient', '=', $idPatient)
            ->whereDate('dateCreate', $dateTimeString)
            ->select("heartbeat.hbValue", "heartbeat.dateCreate")
            ->get();

        $groupedHeartbeats = $heartbeats->groupBy(function ($heartbeat) {
            return Carbon::parse($heartbeat->dateCreate)->format('F j, Y');
        });

        $result = [];
        foreach ($groupedHeartbeats as $month => $heartbeats) {
            $hbValues = $heartbeats->pluck('hbValue');
            $dateCreates = $heartbeats->pluck('dateCreate');

            $result[] = [
                'month' => $month,
                'heartbeats' => [
                    'hbValue' => $hbValues,
                    'dateCreate' => $dateCreates
                ]
            ];
        }

        return response()->json([
            'heartbeats' => $result
        ]);
    }

    public function lastHBRecord(string $idPatient)
    {
        $lastHeartbeat = HeartBeat::where('idPatient', $idPatient)
            ->orderBy('dateCreate', 'desc')
            ->take(10)
            ->select("heartbeat.hbValue", "heartbeat.dateCreate")
            ->get();

        $filteredHeartBeat = collect([]);
        foreach ($lastHeartbeat as $key => $heartbeat) {
            if ($key === 0) {
                $filteredHeartBeat->push($heartbeat);
            } else {
                $prevHeartbeat = $lastHeartbeat[$key - 1];

                $date1 = Carbon::parse($heartbeat->dateCreate);
                $date2 = Carbon::parse($prevHeartbeat->dateCreate);

                $diffInSeconds = $date2->diffInSeconds($date1);
                if ($diffInSeconds >= 5) {
                    $filteredHeartBeat->push($heartbeat);
                }
            }
        }

        $hbValue = $lastHeartbeat->map(function ($heartbeat) {
            return $heartbeat->hbValue;
        })->sort()->values();

        $dateCreate = $lastHeartbeat->map(function ($heartbeat) {
            return $heartbeat->dateCreate;
        })->sort()->values();
        return response()->json([
            'hbValue' => $hbValue,
            'dateCreate' => $dateCreate,
        ]);
    }

    public function getHeartBeatForDateRange(Request $request, $idPatient)
    {
        $heartbeats = HeartBeat::where('idPatient', $idPatient)
            ->whereBetween('dateCreate', [$request->startDate, $request->endDate . ' 23:59:59'])
            ->orderBy('dateCreate')
            ->select("heartbeat.hbValue", "heartbeat.dateCreate")
            ->get();

        $groupedHeartbeats = $heartbeats->groupBy(function ($heartbeat) {
            return Carbon::parse($heartbeat->dateCreate)->format('F Y');
        });

        $result = [];
        foreach ($groupedHeartbeats as $month => $heartbeats) {
            $hbValues = $heartbeats->pluck('hbValue');
            $dateCreates = $heartbeats->pluck('dateCreate');

            $result[] = [
                'month' => $month,
                'heartbeats' => [
                    'hbValue' => $hbValues,
                    'dateCreate' => $dateCreates
                ]
            ];
        }

        return response([
            'heartbeats' => $result
        ], 200);
    }
}
