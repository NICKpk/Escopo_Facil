<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Verifica se o utilizador está autenticado e tem um plano associado
        if (!$user || !$user->plan_id) {
            return redirect()->route('admin.plans.index')
                ->with('error', 'Precisas de um plano ativo para aceder a esta funcionalidade.');
        }

        return $next($request);
    }
}
