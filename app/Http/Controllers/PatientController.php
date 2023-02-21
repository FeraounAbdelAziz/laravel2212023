<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return DB::table('patient')
        ->join('person', 'patient.idPerson', '=', 'person.idPerson')
        ->select('person.*','patient.idPatient' , 'patient.assignmentStatus')
        ->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return Patient::create($request->all());

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Patient::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Patient::find($id);
        $product->update($request->all());
        return $product;
    }


    public function search($name){
        return Patient::where('firstName','like','%'.$name.'%')->get();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Patient::destroy($id);
    }
}
