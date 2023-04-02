<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login (Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => "required|email",
            "password" => 'required',
         ]);
        if ($validator->fails()) {
            return response()->json(['', 'errors' => $validator->errors()]);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
            return response()->json(["status" => "ok", "data" => [ "token" => $token ]]);
        } else {
            return response()->json(["status" => "error", "message" => "Ошибка авторизации"]);
        }


//        $userUser::where([['email', ], ['password', ]]);

    }
}
