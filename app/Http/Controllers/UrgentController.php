<?php

namespace App\Http\Controllers;

use App\Models\Shock;
use App\Models\Temperature;
use App\Models\Environment;


class UrgentController extends Controller
{

    public function urgentCase()
    {
        $temperature = Temperature::where('tempValue', '>', 42)->get();
        $environment = Environment::where('tempEnvValue', '>', 50)->get();
        $shock = Shock::where('shockValue', '=', 1)->get();
        return response()->json([
            'notification' => [
                'temperature' => $temperature,
                'environment' => $environment,
                'shock' => $shock,
            ]

        ]);
    }

}
