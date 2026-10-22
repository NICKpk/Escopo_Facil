<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Support\AuthModal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        return AuthModal::view($request, 'login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse|JsonResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        if (! $request->user()->hasVerifiedEmail()) {
            $intendedPath = parse_url($request->session()->get('url.intended', ''), PHP_URL_PATH) ?? '';
            if (! str_starts_with($intendedPath, '/verify-email/')) {
                return $request->expectsJson()
                    ? AuthModal::json($request, 'verify-email', 'Confirme seu e-mail para acessar o sistema.')
                    : redirect()->route('verification.notice');
            }
        }

        $redirect = redirect()->intended(route('dashboard', absolute: false));

        if ($request->expectsJson()) {
            return response()->json(['redirect' => $redirect->getTargetUrl(), 'csrf' => $request->session()->token(), 'authenticated' => true, 'verified' => $request->user()->hasVerifiedEmail()]);
        }

        return $redirect;
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse|JsonResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $request->expectsJson()
            ? AuthModal::json($request, 'login', 'Você saiu da sua conta.')
            : redirect('/');
    }
}
