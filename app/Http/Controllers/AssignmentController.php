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
    public function assignment($id)
    {
        return DB::table('assignment')
            ->join('patient', 'patient.idPatient', '=', 'assignment.idPatient')
            ->join('person', 'person.idPerson', '=', 'patient.idPerson')
            ->select( 'assignment.*','person.*')
            ->where('idDevice', '=', $id)
            ->get();
    }



     public function addAssignment(Request $request){
        $request->validate([
            'idDevice' => 'required|string',
            'idPatient' => 'required|string',
            'returnDate' => 'required|string',
        ]);
        $Device = Device::findOrFail($request->idDevice);
        if($Device->assignmentStatus){
            return response()->json([
                "message" => 0
            ],401);
        }
        $Patient = Patient::findOrFail($request->idPatient);
        if($Patient->assignmentStatus){
            return response()->json([
                "message" => 0
            ],401);
        }
        Assignment::create([
            'idDevice' => $request->idDevice,
            'idPatient' => $request->idPatient,
            'returnDate' => $request->returnDate,
        ]);
     }

}
