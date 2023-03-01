<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{

    public function index(){
        return DB::table('patient')
            ->join('person', 'patient.idPerson', '=', 'person.idPerson')
            ->select('patient.*','person.*' )
            ->get();
    }

    public function store(Request $request){
        $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'birthdate' => 'required|date',
            'telNum' => 'required',
            'adress' => 'required',
            'email' => 'required|email',
        ]);

        $person = Person::create($request->only(['firstName', 'lastName', 'birthdate', 'telNum', 'adress', 'email']));
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
            'email' => 'required|email',
        ]);

        // Update the person's information
        $person = Person::findOrFail($id);
        $person->update($request->only(['firstName', 'lastName', 'birthdate', 'telNum', 'adress', 'email']));

        // Update the patient's assignment status
        $patient = DB::table('patient')
            ->where('idPerson', '=', $id)
            ->update(['assignmentStatus' => $request->input('assignmentStatus', false)]);

        return response()->json($patient);
    }
 //TODO LATER search
    public function search($name)
    {
        return Patient::where('firstName', 'like', '%' . $name . '%')->get();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Patient::destroy($id);
    }
}
