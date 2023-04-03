<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Validation\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected function validate(Request $request, array $rules) {
        $validator = $this->createValidator($request, $rules);
        $this->checkValidationError($validator);
    }
    protected function createValidator (Request $request, array $rules): Validator {
        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
    }

    protected function checkValidationError (Validator $validator) {
        if ($validator->fails()) {
            return ["error" => ["code" => 402, "message" => "Ошибка валидации", "errors" => $validator->errors()]];
        } else {
            return false;
        }
    }
}
