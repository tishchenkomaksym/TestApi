<?php


namespace App\Http\Services\Api;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthService
{
    public function validateToken(Request $request)
    {
        $validator = Validator::make($request->all(), array_merge([
            'email' => ['required', 'string', 'email', 'max:255']
        ], $this->getSameRequestFields()));

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
    }

    public function validateRegister(Request $request)
    {
        $validator = Validator::make($request->all(), array_merge([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ], $this->getSameRequestFields()));

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
    }

    private function getSameRequestFields():array
    {
        return [
            'password' => ['required', 'string', 'min:8'],
            'device_name' => ['required', 'string']
        ];
    }
}
