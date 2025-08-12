<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Auth;

class LoginController extends Controller
{
    public function User(Request $request)
    {
        return $request->user();
    }
    public function TokensCreate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = $request->user();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json(['access_token' => $token,"token_type"=>Bearer,'success' => true, ], 200);
        } 
    return response()->json(['mensage'=>"Usuario Invalido"], 401);
}
}