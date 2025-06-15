<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = [
            'name'      => 'required',
            'username' => 'required|email',
            'password' => 'required|string',
            'role'      => 'required'
        ];

        $user = User::create([
            'name'  => $request->name,
            'username'=> $request->username,
            'password'=> Hash::make($request->password),
            'role'  => $request->role
        ]);

        return response()->json([
            'message' => 'Register Success',
            'user' => $user
        ]);
    }

    public function login()
    {
        $validator = [
            'email' => 'required|email',
            'password' => 'required|string'
        ];

        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }

        if(!Auth::attempt($request->only('username','password'))){
            return response()->json(
            [
                'message'   => 'Unauthorized'
            ],401);
        }

        $sesi = User::where('username',$request->username)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login success',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }
}
