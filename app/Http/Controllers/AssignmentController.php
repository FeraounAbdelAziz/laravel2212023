<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return DB::table('assignment')
            ->join('patient', 'patient.idPatient', '=', 'assignment.idPatient')
            ->select('assignment.idPatient', 'assignment.idDevice')
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function affectation($id)
    {
        return DB::table('assignment')
            ->join('patient', 'patient.idPatient', '=', 'assignment.idPatient')
            ->join('person', 'person.idPerson', '=', 'patient.idPerson')
            ->select( 'assignment.*','person.*')
            ->where('idDevice', '=', $id)
            ->get();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
