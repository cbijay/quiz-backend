<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = auth()->user();
            $token = $user->createToken('Personal Access Token')->accessToken;

            return response()->json([
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'mobile'    => $user->mobile,
                'address'   => $user->address,
                'city'  => $user->city,
                'role'  => $user->role,
                'status'    =>  $user->status,
                'token' =>  $token
            ], 200);
        }

        return response()->json([
            'message' => 'Invalid credentials',
        ], 400);
    }
}