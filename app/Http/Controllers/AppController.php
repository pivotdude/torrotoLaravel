<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Smena;

class AppController extends Controller
{
    public function getAllUsers (Request $request): JsonResponse {
        return response()->json(["data" => User::all()]);
    }
    public function createSchema (Request $request) {
        $validator = Validator::make($request->all(), [
            'start' => 'required',
            'end' => 'required',
        ]);

        $errors = parent::checkValidationError($validator);
        if ($errors) {
            return response()->json($errors);
        }

        $startDateTime = new \DateTime($request->start);
        $endDateTime = new \DateTime($request->end);
        $now = new \DateTime();



        if ($startDateTime < $now) {
            return response()->json(["error" => ["code" => 402, "message" => "Ошибка валидации", "errors" => ["start" => "Дата меньше текущей"]]]);
        }
        if ($endDateTime < $startDateTime) {
            return response()->json(["error" => ["code" => 402, "message" => "Ошибка валидации", "errors" => ["start" => "Дата старта больше, даты окончания смены"]]]);
        }
        $smena = Smena::create($request->all());

        return response()->json(["data" => $smena]);
    }

    public function openSmena(Request $request, int $smenaId):JsonResponse
    {

        if (Smena::where('active', true)->first()) {
            return response()->json(["error" => ["code" => 402, "message" => "Есть открытая смена"]], 402);
        }

        $smena = Smena::where('id', $smenaId)->update(['active' => 1]);
        return response()->json(["data" => Smena::find($smenaId)]);
    }
    public function closeSmena(Request $request, int $smenaId):JsonResponse
    {

        $smena = Smena::where('id', $smenaId)->update(['active' => 0]);
        return response()->json(["data" => Smena::find($smenaId)]);
    }

    public function addEmployeeOnSmena(Request $request, int $smenaId)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        $errors = parent::checkValidationError($validator);
        if ($errors) {
            return response()->json($errors);
        }
        $smena = Smena::where('id', $smenaId)->first();
        if ($smena) {
            if ($smena->user_id) {
                return response()->json(["data" => "Пользователь уже назначен"]);
            } else {
                try {
                    Smena::where('id', $smenaId)->update(['user_id' => $request->user_id]);
                    return response()->json(["data" => "Пользователь добавлен"]);
                } catch (QueryException $e) {
                    return response()->json(["data" => "Пользователь не найден"]);
                }
            }
        } else {
            return response()->json(["data" => "Смена не найдена"]);
        }
    }

    public function getOrderBySmenaId(int $smenaId)
    {
        $smena = Smena::find($smenaId);
        $orders = Order::where('smena_id', $smenaId)->get();
        $smena->orders = $orders;
        return response()->json(["data" => $smena]);
    }

}
