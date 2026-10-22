<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Support\AuthModal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $request->expectsJson()
                ? AuthModal::json($request, 'email-verified')
                : redirect()->intended(route('dashboard', absolute: false));
        }

        $request->user()->sendEmailVerificationNotification();

        return $request->expectsJson()
            ? AuthModal::json($request, 'verify-email', 'Um novo link de confirmação foi enviado para seu e-mail.')
            : back()->with('status', 'verification-link-sent');
    }
}
