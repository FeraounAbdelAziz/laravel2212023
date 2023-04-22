<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Device;
use App\Models\Patient;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{

    public function index()
    {
        return DB::table('assignment')
            ->join('patient', 'patient.idPatient', '=', 'assignment.idPatient')
            ->select('assignment.idPatient', 'assignment.idDevice')
            ->get();
    }
    public function assignment(string $id)
    {
        return DB::table('assignment')
            ->join('patient', 'patient.idPatient', '=', 'assignment.idPatient')
            ->join('person', 'person.idPerson', '=', 'patient.idPerson')
            ->select('assignment.*', 'person.*')
            ->where('assignment.idDevice', '=', $id)
            ->get();
    }





    public function addAssignment(Request $request)
    {
        $request->validate([
            'idDevice' => 'required|string',
            'idPatient' => 'required|string',
            'returnDate' => 'required|string',
        ]);
        $Device = Device::findOrFail($request->idDevice);
        if ($Device->assignmentStatus) {
            return response()->json([
                "message" => 0
            ], 401);
        }
        $Patient = Patient::findOrFail($request->idPatient);
        if ($Patient->assignmentStatus) {
            return response()->json([
                "message" => 0
            ], 401);
        }
        Assignment::create([
            'idDevice' => $request->idDevice,
            'idPatient' => $request->idPatient,
            'returnDate' => $request->returnDate,
        ]);
    }


    public function deleteAssignment(string $idAssignment)
    {
        return Assignment::where("idAssignment", $idAssignment)->delete();
    }


    public function getIdDevPat()
    {
        $devices = Device::where('assignmentStatus', 0)->pluck('idDevice');
        $patients = Patient::where('assignmentStatus', 0)->pluck('idPatient');

        $optionsDevice = [];

        foreach ($devices as $device) {
            $optionsDevice[0] = [
                "key" => "Select Device ID",
                "value" => "",
            ];
            $optionsDevice[] = [
                "key" => $device,
                "value" => $device,
            ];
        }
        $optionsPatient = [];

        foreach ($patients as $patient) {
            $optionsPatient[0] = [
                "key" => "Select Patient ID",
                "value" => $patient,
            ];
            $optionsPatient[] = [
                "key" => $patient,
                "value" => $patient,
            ];
        }

        return response()->json([
            "options" => ['optionsDevice' => $optionsDevice, 'optionsPatient' => $optionsPatient]
        ]);
    }



}
