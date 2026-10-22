<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Support\AuthModal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(Request $request): View
    {
        return AuthModal::view($request, 'forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        if (is_string($request->input('email'))) {
            $request->merge(['email' => mb_strtolower(trim($request->input('email')))]);
        }
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        Password::sendResetLink(
            $request->only('email')
        );

        // The same response for unknown addresses prevents account enumeration.
        if ($request->expectsJson()) {
            return AuthModal::json($request, 'reset-link-sent', 'Se o e-mail estiver cadastrado, você receberá as instruções para redefinir sua senha.');
        }

        return back()->with('status', __('passwords.sent'));
    }
}
