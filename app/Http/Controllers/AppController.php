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

    public function addOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'work_shift_id' => 'required|exists:smenas,id',
            'table_id' => 'required|int',
            'count' => 'int',
        ]);
        $errors = parent::checkValidationError($validator);
        if ($errors) {
            return response()->json($errors);
        }

        $shema = Smena::find($request->work_shift_id);

        if ($shema->user_id != $request->user()->id) {
            return response()->json(["error" => ["code" => 403, "message" => "Forbidden. You don't work this shift!"]], 403);
        }
        if ($shema->active == 0) {
            return response()->json(["error" => ["code" => 403, "message" => "Forbidden. The shift must be active!"]], 403);
        }
        $table_title = 'Столик №' . $request->table_id;
        $order = Order::create(['table' => $table_title, 'smena_id' => $request->work_shift_id,'shift_worker' => $request->user()->id]);
        return response()->json(["data" => $order]);

    }

    public function getOrderById(Request $request, int $orderId)
    {
        $order = Order::find($orderId);
        if ($order) {
            if ($order->shift_worker != $request->user()->id) {
                return response()->json(["error" => ['code' => 403, 'message' => 'Forbidden. You did not accept this order!']]);
            } else {
                return response()->json(["data" => $order]);
            }
        } else {
            return response()->json(["error" => ['code' => 401, 'message' => 'Заказ не найден']]);
        }
    }

    public function getOrders(Request $request, int $smenaId)
    {
        $smena = Smena::find($smenaId);
        $smena->user_id;
        $request->user()->id;
        if ($smena->user_id != $request->user()->id) {
            return response()->json(["error" => ["code" => 403,"message" => "Forbidden. You did not accept this order!"]]);
        }
        $orders = Order::where('smena_id', $smenaId)->get();
        $smena->orders = $orders;
        return response()->json(["data" => $smena]);
    }

    public function changeStatus(Request $request, int $orderId)
    {
        if (!$request->status) {
            return response()->json(["error" => ["code" => 403,"message" => "Заказа нет"]]);
        }
        $order = Order::where('id', $orderId)->update(["status" => $request->status]);
        if ($order) {
            return response()->json(["data" => Order::find($orderId)]);
        } else {
            return response()->json(["data" => ['message' => 'Заказ не найден']]);
        }

    }

    public function getOrdersActiveSmena(Request $request)
    {
        $smena = Smena::where('active', 1)->first();
        $smena_id = $smena->id;
        $orders = Order::where('smena_id', $smena_id)->get();
        return response()->json(["data" => $orders]);
    }
}
