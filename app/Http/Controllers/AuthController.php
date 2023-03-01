<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Person;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|string|unique:person,email',
            'password' => 'required|string|confirmed',
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'adress' => 'required|string',
            'birthdate' => 'required|string',
            'telNum' => 'required|string',
        ]);
        $request->validate([
            'email' => 'required|string|unique:person,email',
            'password' => 'required|string|confirmed',
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'adress' => 'required|string',
            'birthdate' => 'required|string',
            'telNum' => 'required|string',
        ]);

        $person = Person::create([
            'email' => $request->email,
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'adress' => $request->adress,
            'birthdate' => $request->birthdate,
            'telNum' => $request->telNum
        ]);

        $doctor = Doctor::create([
            'password' => bcrypt($request->password),
            'idPerson' => $person->idPerson, // set idPerson to the id of the newly created person
        ]);
        // ->sendEmailVerificationNotification();

        $token = $doctor->createToken('DoctorToken' . $person->idPerson)->plainTextToken;
        $doctor->notify(new EmailVerificationNotification());
        $response = [
            'doctor' => $doctor,
            'token' => $token,
        ];
        return response($response, 201);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $person = Person::where('email', $request->email)->first();

        if (!$person) {
            return response([
                'message' => 'Email not found'
            ], 401);
        }
        $doctor = Doctor::where('idPerson', $person->idPerson)->first();
        // return response()->json($doctor);
        if (!$doctor) {
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        if (!Hash::check($request->password, $doctor->password)) {
            return response([
                'message' => 'Password is incorrect'
            ], 401);
        }
        $token = $doctor->createToken('DoctorToken' . $person->idPerson)->plainTextToken;

        $expiresAt = now()->addMinutes(10);
        $response = [
            'doctor' => $doctor,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => $expiresAt->toDateTimeString(),
        ];
        return response($response, 201);
    }
    public function logout(Request $request)
    {

        auth()->user()->tokens()->delete();
        return [
            'message' => 'logged out',
        ];
    }
    public function verify($user_id, Request $request)
    {
        if (!$request->hasValidSignature()) {
            return $this->response(
                ['msg' => 'unauthorized XD'],
                401
            );
        }
        $doctor = Doctor::findOrFail($user_id);
        if (!$doctor->hasVerifiedEmail()) {
            $doctor->markEmailAsVerified();
        }
        return redirect()->to('/');
    }
    public function resend()
    {
        if (auth()->user()->hasVerifiedEmail()) {
            return response(
                ['msg' => 'resend idk XD'],
                401
            );
        }
        auth()->user()->sendEmailVerificationNotification();
        return response(
            ['msg' => 'success XD'],
            200
        );
    }
}
