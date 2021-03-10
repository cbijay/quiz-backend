<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //
    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = auth()->user();
                $token = $user->createToken('Personal Access Token')->accessToken;

                User::where('id', Auth::user()->id)->update(['is_online' => 1]);

                return response()->json([
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'mobile'    => $user->mobile,
                    'address'   => $user->address,
                    'city'  => $user->city,
                    'role'  => $user->role,
                    'status'    =>  $user->status,
                    'is_online' => $user->is_online,
                    'image' => $user->user_img,
                    'token' =>  $token
                ], 200);
            }

            return response()->json([
                'message' => 'Invalid credentials',
            ], 400);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        }
    }
}
