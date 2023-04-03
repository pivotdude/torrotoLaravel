<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function login (Request $request) {
        $validator = Validator::make($request->all(), ['login' => "required", "password" => 'required']);

        $errorsResp = parent::checkValidationError($validator);
        if ($errorsResp) {
            return response()->json($errorsResp);
        }

        $user = User::where($request->all())->first();

        if ($user) {;
            $token = $user->createToken('token')->plainTextToken;
            return response()->json(["data" => [ "user_token" => $token ]]);
        } else {
            return response()->json(["error" => ["code" => 401, "message" => "Authentication failed"]], 404);
        }
    }

    public function logout (Request $request) {
        return response()->json(["data" => ["message" => "logout"]]);
    }
    public function registration (Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => "required|string",
            'surname' => "nullable|string",
            'patronymic ' => "nullable|string",
            "login" => 'required|string|unique:users,login',
            "password" => "required|string",
            "photo_file" => "nullable|image|max:2048",
            "role_id" => "required|integer",
        ]);

        $errorsResp = parent::checkValidationError($validator);
        if ($errorsResp) {
            return response()->json($errorsResp);
        }

        if ($request->photo_file) {
            $photoName = time() . '-' . $request->photo_file->extension();
            $request->photo_file->move(public_path('photos'));
            $request->photo_file = $photoName;
        }

        $user = User::create($request->all());



        return response()->json(["data" => ["id" => $user->id, "status" => "created"]]);

    }
}
