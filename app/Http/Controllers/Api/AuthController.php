<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'user',
        ]);
    
        $token = $user->createToken('auth_token')->plainTextToken;
    
        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Credenciales incorrectas.'],
            ]);
        }

        $token = $user->createToken('sona-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente'
        ]);
    }


    public function registerWithPayment(Request $request)
    {
        // Validación básica
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'paypalDetails' => 'required|array'
        ]);
    
        $paypalDetails = $request->paypalDetails;
    
        // Validar que la orden esté completada
        if (!isset($paypalDetails['status']) || $paypalDetails['status'] !== 'COMPLETED') {
            return response()->json(['message' => 'Pago no válido'], 400);
        }
    
        // Validar monto
        $amount = $paypalDetails['purchase_units'][0]['amount']['value'] ?? null;
        if ($amount != "1.99") {
            return response()->json(['message' => 'Monto incorrecto'], 400);
        }
    
        // Crear usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'user',
            'paypal_order_id' => $paypalDetails['id'] ?? null,
            'paypal_payer_email' => $paypalDetails['payer']['email_address'] ?? null,
            'paypal_status' => $paypalDetails['status'] ?? null,
        ]);
    
        $token = $user->createToken('auth_token')->plainTextToken;
    
        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }


    private function generatePayPalAccessToken()
    {
        $response = Http::withBasicAuth(
            env('PAYPAL_CLIENT_ID'),
            env('PAYPAL_SECRET')
        )->asForm()->post(env('PAYPAL_BASE_URL') . '/v1/oauth2/token', [
            'grant_type' => 'client_credentials'
        ]);

        return $response->json()['access_token'];
    }

}