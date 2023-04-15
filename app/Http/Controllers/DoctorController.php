<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DoctorController extends Controller   
{
    public function displayAccounts()
    {
        return DB::table('doctor')
            ->join('person', 'doctor.idPerson', '=', 'person.idPerson')
            ->select('person.*','doctor.idDoctor','doctor.email','doctor.isVerified')
            ->get();
    }

    public function createAdmin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|unique:doctor,email',
            'password' => 'required|string|confirmed',
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'adress' => 'required|string',
            'birthdate' => 'required|string',
            'telNum' => 'required|string',
        ]);
        $existEmail = Doctor::where('email', $request->email)->first();
        if ($existEmail) {
            return response()->json([
                'error' => "already existing",
            ]);
        }
        $person = Person::create([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'adress' => $request->adress,
            'birthdate' => $request->birthdate,
            'telNum' => $request->telNum,
        ]);
        Doctor::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'idPerson' => $person->idPerson,
        ]);

        $response = [
            'status' => "success",
        ];
        return response($response, 201);
    }
    public function updateUser(Request $request, string $id)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required',
            'telNum' => 'required',
        ]);

        $doctor = Doctor::findOrFail($id);
        $doctor->person->update($request->only(['telNum']));

        $newPassword = Hash::make($request->password);

        $doctor->update([
            'email' => $request->email,
            'password' => $newPassword,
        ]);

        return response()->json($doctor);
    }




    public function deleteUser(string $id)
    {
        return Doctor::destroy($id);
    }
}
