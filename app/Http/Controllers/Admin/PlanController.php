<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Lista todos os planos disponíveis.
     */
    public function index()
    {
        $plans = Plan::all();
        return view('admin.plans.index', compact('plans'));
    }

    /**
     * Mostra o formulário para criar um novo plano.
     */
    public function create()
    {
        return view('admin.plans.create');
    }

    /**
     * Guarda o novo plano na base de dados.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:plans',
            'description' => 'nullable|string',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
        ]);

        Plan::create($request->only(['name', 'slug', 'description', 'price_monthly', 'price_yearly']));

        return redirect()->route('admin.plans.index')->with('success', 'Plano criado com sucesso!');
    }

    /**
     * Mostra o formulário para editar um plano existente.
     */
    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    /**
     * Atualiza o plano na base de dados.
     */
    public function update(Request $request, Plan $plan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:plans,slug,' . $plan->id,
            'description' => 'nullable|string',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
        ]);

        $plan->update($request->only(['name', 'slug', 'description', 'price_monthly', 'price_yearly']));

        return redirect()->route('admin.plans.index')->with('success', 'Plano atualizado com sucesso!');
    }

    /**
     * Remove o plano da base de dados.
     */
    public function destroy(Plan $plan)
    {
        $plan->delete();

        return redirect()->route('admin.plans.index')->with('success', 'Plano removido com sucesso!');
    }
}
