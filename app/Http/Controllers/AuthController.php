<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string|unique:doctor,email',
            'password' => 'required|string|confirmed',
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'adress' => 'required|string',
            'birthdate' => 'required|string',
            'telNum' => 'required|string',
        ]);
        $user = Doctor::create([
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);
        $token = $user->createToken('DoctorToken')->plainTextToken;
        $response = [
            'doctor' => $user,
            'token' => $token
        ];
        return response($response,201);
    }
}
