<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    public function sendCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'error' => 'USER_NOT_FOUND'
            ], 404);
        }

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        DB::table('password_reset_codes')->where('email', $request->email)->delete();

        DB::table('password_reset_codes')->insert([
            'email' => $request->email,
            'code' => Hash::make($code),
            'created_at' => now(),
        ]);

        Mail::send('emails.reset-code', ['code' => $code], function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Sona - Password Reset Code');
        });

        return response()->json(['sent' => true]);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'string', 'size:6'],
        ]);

        $record = DB::table('password_reset_codes')
            ->where('email', $request->email)
            ->first();

        if (!$record) {
            return response()->json(['error' => 'INVALID_CODE'], 422);
        }

        if (now()->diffInMinutes($record->created_at) > 15) {
            DB::table('password_reset_codes')->where('email', $request->email)->delete();
            return response()->json(['error' => 'CODE_EXPIRED'], 422);
        }

        if (!Hash::check($request->code, $record->code)) {
            return response()->json(['error' => 'INVALID_CODE'], 422);
        }

        return response()->json(['verified' => true]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'string', 'size:6'],
            'password' => ['required', 'min:8'],
        ]);

        $record = DB::table('password_reset_codes')
            ->where('email', $request->email)
            ->first();

        if (!$record) {
            return response()->json(['error' => 'INVALID_CODE'], 422);
        }

        if (now()->diffInMinutes($record->created_at) > 15) {
            DB::table('password_reset_codes')->where('email', $request->email)->delete();
            return response()->json(['error' => 'CODE_EXPIRED'], 422);
        }

        if (!Hash::check($request->code, $record->code)) {
            return response()->json(['error' => 'INVALID_CODE'], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'USER_NOT_FOUND'], 404);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        DB::table('password_reset_codes')->where('email', $request->email)->delete();

        return response()->json(['reset' => true]);
    }
}