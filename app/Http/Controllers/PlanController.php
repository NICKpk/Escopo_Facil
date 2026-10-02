<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;

class PlanController extends Controller
{
    public function index()
    {
        // Busca todos os planos cadastrados na base de dados
        $plans = Plan::all();

        // Retorna os dados em formato JSON para testar, podemos preparar para exibir numa view futuramente.
        return response()->json([
            'success' => true,
            'data' => $plans
        ]);
    }
}
