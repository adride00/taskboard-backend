<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }


    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->validated();
            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'message' => 'Credenciales incorrectas'
                ], 401);
            }

            $user = User::where('email', $credentials['email'])->first();
            $token = $user->createToken('auth_token')->plainTextToken;
            // obteniendo datos del usuario
            $user = Auth::user();
            if ($user->status == 'inactivo') {
                return response()->json([
                    'message' => 'Usuario inactivo'
                ], 401);
            }
            return response()->json([
                'message' => 'Login correcto',
                'data' => $user,
                'access_token' => $token
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error login',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}
