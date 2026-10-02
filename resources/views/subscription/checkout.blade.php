<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checkout e Confirmação de Assinatura') }}
        </h2>
    </x-slot>

    <!--[FRONT-END GUI]: Tela de checkout e confirmação -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">

                <h3 class="text-lg font-bold mb-4">Resumo da Assinatura</h3>

                <div class="border p-4 rounded mb-6 bg-gray-50">
                    <p class="text-xl font-semibold text-indigo-600">{{ $plan->name }}</p>
                    <p class="text-gray-600 mt-1">{{ $plan->description ?? 'Plano de subscrição Escopo Fácil' }}</p>
                    <p class="text-2xl font-bold mt-4">R$ {{ number_format($plan->price, 2, ',', '.') }}</p>
                </div>

                <!-- Formulário de Envio para o Processamento -->
                <form action="{{ route('subscription.process') }}" method="POST">
                    @csrf
                    <!-- Enviamos o ID do plano selecionado de forma oculta -->
                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">

                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">
                            Confirmar Assinatura
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
