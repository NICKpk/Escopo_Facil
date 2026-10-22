<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthModal
{
    public static function view(Request $request, string $screen, array $data = []): View
    {
        $email = $request->user()?->email ?? $request->input('email', old('email', ''));
        return view('welcome', array_merge([
            'authScreen' => $screen,
            'authEmail' => is_string($email) ? $email : '',
        ], $data));
    }

    public static function json(Request $request, string $screen, string $message = '', array $data = []): JsonResponse
    {
        $email = $request->user()?->email ?? $request->input('email', '');
        return response()->json(array_merge([
            'screen' => $screen,
            'message' => $message,
            'csrf' => $request->session()->token(),
            'email' => is_string($email) ? $email : '',
            'authenticated' => $request->user() !== null,
            'verified' => $request->user()?->hasVerifiedEmail() ?? false,
        ], $data));
    }
}
