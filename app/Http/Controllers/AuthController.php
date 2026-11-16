<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Iniciar sesión y emitir token Sanctum.
     * POST /api/login
     */
    public function login(Request $request)
    {
        $request->validate([ ## validacion de datos de entrada
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) { ## verificacion de credenciales (o existencia del usuario)
            return response()->json([
                'message' => 'Credenciales inválidas.',
            ], 401);
        }

        // Generar token Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        // Castear a subclase (Cliente o Tecnico)
        $instancia = $user->toSubclass();

        return response()->json([ ## respuesta en json de los datos del usuario y el token
            'token' => $token,
            'user'  => [
                'id'     => $instancia->id,
                'name'   => $instancia->name,
                'email'  => $instancia->email,
                'tipo'   => $instancia->tipo,
                'clase'  => class_basename($instancia),
            ],
        ], 200);
    }

    /**
     * Obtener los datos del usuario autenticado con su subclase.
     * GET /api/user
     */
    public function user(Request $request)
    {
        $instancia = $request->user()->toSubclass();

        return response()->json([ ## respuesta en json de los datos del usuario autenticado
            'id'     => $instancia->id,
            'name'   => $instancia->name,
            'email'  => $instancia->email,
            'tipo'   => $instancia->tipo,
            'clase'  => class_basename($instancia),
        ], 200);
    }

    /**
     * Cerrar sesión invalidando el token actual.
     * POST /api/logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete(); ## elimina el token actual del usuario autenticado

        return response()->json([
            'message' => 'Sesión cerrada correctamente.',
        ], 200);
    }
}