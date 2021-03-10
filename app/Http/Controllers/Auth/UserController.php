<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    public function logout(Request $request)
    {
        try {
            return auth()->user();
            /* User::where('id', Auth::user()->id)->update(['is_online' => 0]);
            $accessToken = Auth::user()->token();
            $logout = $accessToken->revoke();


            if ($logout) {
                return response()->json(true);
            } */
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        }
    }
}