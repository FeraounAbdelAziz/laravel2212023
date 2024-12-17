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
        $doctorFullName = DB::table('doctor')
        ->where('doctor.idDoctor', '=', '1')
        ->join('person', 'doctor.idPerson', '=', 'person.idPerson')
        ->select('person.firstName', 'person.lastName')->get();

        $assignedPatients = DB::table('patient')
            ->join('person', 'patient.idPerson', '=', 'person.idPerson')
            ->join('assignment', 'patient.idPatient', '=', 'assignment.idPatient')
            ->select('patient.*', 'person.*', 'assignment.*')
            ->get();
        foreach ($assignedPatients as $assigned) {
            $doctorFullName = DB::table('doctor')
                ->where('doctor.idDoctor', '=', $assigned->idDoctor)
                ->join('person', 'doctor.idPerson', '=', 'person.idPerson')
                ->select('person.firstName', 'person.lastName')->first();

                $assigned->doctorFullName =$doctorFullName->firstName .''.$doctorFullName->lastName;
        }
        $unassignedPatients = DB::table('patient')
            ->join('person', 'patient.idPerson', '=', 'person.idPerson')
            ->where('patient.assignmentStatus', '0')
            ->select('patient.*', 'person.*')
            ->get();
        foreach ($unassignedPatients as $unassigned) {
            if ($unassigned->assignmentStatus === 0) {
                $unassigned->idDevice = "null";
                $unassigned->idAssignment = "Not assigned yet";

                $doctorFullName = DB::table('doctor')
                ->where('doctor.idDoctor', '=', $unassigned->idDoctor)
                ->join('person', 'doctor.idPerson', '=', 'person.idPerson')
                ->select('person.firstName', 'person.lastName')
                ->first();

                $unassigned->doctorFullName =$doctorFullName->firstName .''.$doctorFullName->lastName;
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
            'idDoctor' => '1',
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
        // Validate only the fields that need to be updated
        $request->validate([
            'telNum' => 'required|string',
            'adress' => 'required|string',
        ]);
    
        // Find the patient using the provided ID
        $patient = Patient::findOrFail($id);
    
        // Update the related person's details
        DB::table('person')
            ->where('idPerson', $patient->idPerson) // Assuming 'idPerson' links 'person' and 'patient'
            ->update([
                'telNum' => $request->input('telNum'),
                'adress' => $request->input('adress'),
            ]);
    
        // Retrieve the updated person details, excluding the `id` column
        $person = DB::table('person')
            ->where('idPerson', $patient->idPerson)
            ->select('*') // Replace 'other_columns' with any other fields to include
            ->first();
    
        // Return the person's details as JSON
        return response()->json($person);
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
