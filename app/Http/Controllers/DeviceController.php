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
        return Device::get()->where('idDevice' , '=' , $id);
    }
    // TODO STORE DEVICE
    public function store(Request $request)
    {

        $data = $request->validate([
            'idDevice' => 'required|integer|unique:device,idDevice',
        ]);
        $device = Device::create([
            'idDevice' => $data['idDevice'],
            'assignmentStatus' => false,
            'isOnline' => false,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $device,
        ]);
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
