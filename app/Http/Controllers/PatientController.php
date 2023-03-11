<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{

    public function index()
    {
        $assignedPatients = DB::table('patient')
            ->join('person', 'patient.idPerson', '=', 'person.idPerson')
            ->join('assignment', 'patient.idPatient', '=', 'assignment.idPatient')
            ->select('patient.*', 'person.*', 'assignment.*')
            ->get();
        $unassignedPatients = DB::table('patient')
            ->join('person', 'patient.idPerson', '=', 'person.idPerson')
            ->where('patient.assignmentStatus', '0')
            // ->leftJoin('assignment', 'patient.idPatient', '=', 'assignment.idPatient')
            // ->whereNull('assignment.idAssignment')
            ->select('patient.*', 'person.*')
            ->get();
        foreach ($unassignedPatients as $unassigned) {
            if ($unassigned->assignmentStatus === 0) {
                $unassigned->idDevice = "null";
                $unassigned->idAssignment = "Not assigned yet";
            }
        }
        $allPatients = array_merge($assignedPatients->toArray(), $unassignedPatients->toArray());
        return response([
            "patients" => $allPatients
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'birthdate' => 'required|date',
            'telNum' => 'required',
            'adress' => 'required',
        ]);
        $personData = $request->only(['firstName', 'lastName', 'telNum', 'adress']);
        $personData['birthdate'] = date_format(date_create($request->birthdate), 'Y-m-d');

        $person = Person::create($personData);

        DB::table('patient')->insert([
            'idPerson' => $person->idPerson,
            'assignmentStatus' => false,
        ]);
        $patient = DB::table('patient')
            ->join('person', 'patient.idPerson', '=', 'person.idPerson')
            ->where('patient.idPerson', '=', $person->idPerson)
            ->select('patient.idPatient', 'person.*')
            ->first();
        return response()->json($patient);
    }


    public function show(string $id)
    {
        return DB::table('patient')
            ->join('person', 'patient.idPerson', '=', 'person.idPerson')
            ->where('patient.idPatient', '=', $id)
            ->select('patient.*', 'person.*')
            ->get();
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'birthdate' => 'required|date',
            'telNum' => 'required',
            'adress' => 'required',
        ]);

        // Update the person's information
        $person = Person::findOrFail($id);
        $person->update($request->only(['firstName', 'lastName', 'birthdate', 'telNum', 'adress']));

        // Update the patient's assignment status
        $patient = DB::table('patient')
            ->where('idPerson', '=', $id)
            ->update(['assignmentStatus' => $request->input('assignmentStatus', false)]);

        return response()->json($patient);
    }
    //TODO LATER search
    public function search($telNum)
    {
        return Patient::where('telNum', 'like', '%' . $telNum . '%')->get();
    }

    public function destroy(string $id)
    {
        return Patient::destroy($id);
    }
}
