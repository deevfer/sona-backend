<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PremiumAccess;

class EnsurePremiumAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $premium = PremiumAccess::where('user_id', $user->id)
            ->where(function ($query) {
                $query->where('type', 'lifetime')
                      ->orWhere(function ($q) {
                          $q->where('type', 'subscription')
                            ->where('ends_at', '>', now());
                      });
            })
            ->exists();

        if (!$premium) {
            return response()->json(['message' => 'Premium access required'], 403);
        }

        return $next($request);
    }
}