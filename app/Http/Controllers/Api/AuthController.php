<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ExternalAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'user',
        ]);

        $deviceName = $request->input('device_name', 'sona-device');
        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'error' => 'INVALID_CREDENTIALS'
            ], 422);
        }
    
        // Borrar tokens sin actividad en las últimas 24 horas
        $user->tokens()
            ->where(function ($query) {
                // $query->where('last_used_at', '<', now()->subHours(24))
                $query->where('last_used_at', '<', now()->subMinutes(1))
                      ->orWhereNull('last_used_at');
            })
            ->delete();
    
        // Bloquear si aún existe una sesión activa (token usado en últimas 24h)
        if ($user->tokens()->exists()) {
            return response()->json([
                'error' => 'SESSION_ACTIVE'
            ], 403);
        }
    
        $deviceName = $request->input('device_name', 'sona-device');
        $token = $user->createToken($deviceName)->plainTextToken;
    
        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }
    public function logout(Request $request)
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'message' => 'Usuario no autenticado',
            ], 401);
        }

        DB::transaction(function () use ($user) {
            // Eliminar conexiones externas del usuario para forzar nueva autorización
            ExternalAccount::where('user_id', $user->id)->delete();

            // Eliminar token actual de Sanctum
            $currentToken = $user->currentAccessToken();
            if ($currentToken) {
                $currentToken->delete();
            }

            // Eliminar sesiones guardadas
            DB::table('sessions')
                ->where('user_id', $user->id)
                ->delete();
        });

        return response()->json([
            'message' => 'Sesión cerrada correctamente y proveedores desvinculados',
        ]);
    }

    public function registerWithPayment(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8'],
            'orderID' => ['required'],
        ]);

        $accessToken = $this->generatePayPalAccessToken();

        $response = Http::withToken($accessToken)
            ->get(env('PAYPAL_BASE_URL') . "/v2/checkout/orders/{$request->orderID}");

        $order = $response->json();

        if (! $order || ($order['status'] ?? null) !== 'COMPLETED') {
            return response()->json([
                'message' => 'Pago no válido',
            ], 400);
        }

        $amount = $order['purchase_units'][0]['amount']['value'] ?? null;

        if ($amount != "2.99") {
            return response()->json([
                'message' => 'Monto incorrecto',
            ], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'user',
        ]);

        $payment = \App\Models\Payment::create([
            'user_id' => $user->id,
            'provider' => 'paypal',
            'provider_transaction_id' => $order['id'],
            'amount' => $amount,
            'currency' => $order['purchase_units'][0]['amount']['currency_code'],
            'status' => $order['status'],
            'raw_response' => json_encode($order),
        ]);

        \App\Models\PremiumAccess::create([
            'user_id' => $user->id,
            'payment_id' => $payment->id,
            'type' => 'lifetime',
            'starts_at' => now(),
            'ends_at' => null,
        ]);

        $deviceName = $request->input('device_name', 'sona-device');
        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    private function generatePayPalAccessToken()
    {
        $response = Http::withBasicAuth(
            env('PAYPAL_CLIENT_ID'),
            env('PAYPAL_SECRET')
        )->asForm()->post(env('PAYPAL_BASE_URL') . '/v1/oauth2/token', [
            'grant_type' => 'client_credentials',
        ]);

        return $response->json()['access_token'];
    }
}