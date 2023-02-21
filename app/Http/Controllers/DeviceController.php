<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function deviceAffect(){
        return DB::table('Assignment')
            ->join('device', 'device.idDevice', '=', 'Assignment.idDevice')
            ->join('patient', 'patient.idPatient', '=', 'Assignment.idPatient')
            ->join('person', 'patient.idPerson', '=', 'person.idPerson')
            ->select('patient.*', 'device.*', 'person.*')
            ->get();
    }
    public function allDevices(){
        return Device::all();
    }
    public function device($id){
        return Device::get()->where('idAppareil' , '=' , $id);
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
