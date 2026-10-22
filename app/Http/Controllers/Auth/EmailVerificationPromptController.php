<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Support\AuthModal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): JsonResponse|View
    {
        $screen = $request->user()->hasVerifiedEmail() ? 'email-verified' : 'verify-email';

        return $request->expectsJson()
            ? AuthModal::json($request, $screen)
            : AuthModal::view($request, $screen);
    }
}
