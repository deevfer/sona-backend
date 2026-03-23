<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppleMusicWebAuthController extends Controller
{
    public function androidConnectPage(Request $request)
    {
        $token = $request->query('token');

        if (!$token) {
            abort(400, 'Missing token');
        }

        return view('android-connect', [
            'token' => $token,
            'apiBase' => config('app.api_url'),
        ]);
    }
}