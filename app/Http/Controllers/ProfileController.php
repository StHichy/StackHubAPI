<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;


use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Response;
use App\Models\User;
use Laravel\Sanctum\HasApiTokens;
class ProfileController extends Controller
{
    /**
     * Criar um token de autenticação para o usuário logado.
     */
    public function createToken(Request $request)
    {

         $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];
 

        if (Auth::attempt($credentials)) {
            $user = $request->user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                "access_token" => $token,
                "token_type" => 'Bearer'
            ]);
        } else {
            return response()->json([
                "message" => "Usuário ou senha inválidos!"
            ], 401);  // Código 401 para falha de autenticação
        }

    }
}
