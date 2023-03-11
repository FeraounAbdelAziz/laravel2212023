<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailVerificationRequest;
use App\Models\Doctor;
use App\Models\Person;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Otp;


class AuthController extends Controller
{


    private $otp;

    public function __construct()
    {
        $this->otp = new Otp;
    }
    public function register(Request $request)
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
            $existEmail->notify(new EmailVerificationNotification());
            // TODO : update the person profile HERE
            return response()->json([
                'doctor' => $existEmail,
            ]);
        }
        $person = Person::create([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'adress' => $request->adress,
            'birthdate' => $request->birthdate,
            'telNum' => $request->telNum,
        ]);
        $doctor = Doctor::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'idPerson' => $person->idPerson, // set idPerson to the id of the newly created person
        ]);
        $token = Auth::guard('api')->attempt(['email' => $request->email, 'password' => $request->password]);

        $doctor->notify(new EmailVerificationNotification());

        $response = [
            'email' => $doctor->email,
            'access_token' => $token,
        ];
        return response($response, 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $doctor = Doctor::where('email', $request->email)->first();
        if (!$doctor) {
            return response([
                'isUserExist' => 0
            ], 401);
        }

        if (!Hash::check($request->password, $doctor->password)) {
            return response([
                'message' => 'Password is incorrect'
            ], 401);
        }
        $doctor->notify(new EmailVerificationNotification());
        if ($doctor->isVerified) {
            $response = [
                'email' => $doctor->email,
            ];
            return response($response, 200);
        } else {
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }
    }

    public function sendEmailVerification(Request $request)
    {
        $doctor = Doctor::where('email', $request->email)->first();
        $doctor->notify(new EmailVerificationNotification());
        return response()->json([
            'success' => 1,
        ], 200);
    }

    public function checkEmailVerification(EmailVerificationRequest $request)
    {
        $otp = $this->otp->validate($request->email, $request->otp);
        if (!($otp->status)) {
            return response()->json([
                'error' => $otp
            ], 401);
        }
        $doctor = Doctor::where('email', $request->email)->first();
        $doctor->update(['isVerified' => true]);
        return response()->json([
            'access_token' => JWTAuth::fromUser($doctor, ['exp' => now()->addHour()->timestamp]),
        ], 200);
    }

    public function logout(Request $request)
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }
}
