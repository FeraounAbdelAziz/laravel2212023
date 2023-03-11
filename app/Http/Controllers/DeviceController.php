<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DeviceController extends Controller
{

    public function deviceAssignment()
    {
        $assignedDevices = DB::table('Assignment')
            ->join('device', 'device.idDevice', '=', 'Assignment.idDevice')
            ->join('patient', 'patient.idPatient', '=', 'Assignment.idPatient')
            ->join('person', 'patient.idPerson', '=', 'person.idPerson')
            ->select('patient.*', 'device.*', 'person.*', 'assignment.returnDate')
            ->get();
        $unassignedDevices = DB::table('Device')
            ->where('assignmentStatus', 0)->select('device.*')->get();
        foreach ($unassignedDevices as $unassigned) {
            if ($unassigned->assignmentStatus === 0) {
                $unassigned->idPatient = "null";
                $unassigned->idPerson = "null";
                $unassigned->firstName = "null";
                $unassigned->lastName = "null";
                $unassigned->telNum = "null";
                $unassigned->adress = "null";
                $unassigned->birthdate = "null";
                $unassigned->dateCreate = "null";
                $unassigned->returnDate = "null";
            }
        }
        $allDevices = array_merge($assignedDevices->toArray(), $unassignedDevices->toArray());
        return response([
            "allDevices" => $allDevices,
        ]);
    }

    public function search($idDevice)
    {
        return Device::get()->where('idDevice', '=', $idDevice);
    }
    // TODO DONE
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

    public function destroy(string $id)
    {
        return Device::destroy($id);
    }
}
