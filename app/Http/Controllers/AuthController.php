<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
       $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        return response()->json([
            'message' => 'Successfully created user!',
            'user' => $user,
            'token' => $token
        ], 200);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
             'email' => 'required|string',
             'password' => 'required|string',
         ]);
         //check mail
         $user = User::where('email', $fields['email'])->first();
         //check password
         if(!$user || !Hash::check($fields['password'], $user->password)){
            return response([
                'message' => 'Bad creds'
            ],404);
         }
         $token = $user->createToken('authToken')->plainTextToken;      

         return response()->json([
             'message' => 'Successfully logged in',
             'user' => $user,
             'token' => $token
         ], 200);
     }
    

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }
}
