<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:Account,email',
            'password' => 'required|min:6',
            'login' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error', 'errors' => $validator->errors()], 400);
        }

        $user = Account::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'login' => $request->login,
            'role' => 0
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'id' => $user->id
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = auth()->user();



        return response()->json([
            'access_token' => $token,
            'refresh_token' => $token,
            'id' => $user->id
        ]);
    }

    public function getUser()
    {
        return response()->json(auth()->user());
    }

    public function getAllUsers()
    {
        $users = Account::all();
        return response()->json(['accounts' => $users], 200);
    }
}
