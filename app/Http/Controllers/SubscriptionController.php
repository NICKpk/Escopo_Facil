<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    /**
     * Mostra a tela de checkout para um determinado plano.
     */
    public function checkout(Plan $plan)
    {
        return view('subscription.checkout', compact('plan'));
    }

    /**
     * Processa a assinatura (simulação de pagamento / e-commerce).
     */
    public function process(Request $request)
    {
        $request->validate([
            'plan_id' => ['required', 'exists:plans,id'],
        ]);

        $user = Auth::user();

        // Atualiza o plan_id do utilizador logado
        $user->plan_id = $request->plan_id;
        $user->save();

        // Redireciona para a rota nomeada 'subscription.success' (definida no web.php)
        return redirect()->route('subscription.success');
    }

    /**
     * Mostra a página de confirmação de sucesso.
     */
    public function success()
    {
        return view('subscription.success');
    }
}
