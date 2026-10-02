<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Assinatura Confirmada!') }}
        </h2>
    </x-slot>

    <!-- [FRONT-END GUI]: Tela exibida após o sucesso do pagamento. Podes criar um design apelativo com confetis ou ícone de sucesso. -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                <h3 class="text-2xl font-bold text-green-600 mb-2">Parabéns! Assinatura efetuada com sucesso.</h3>
                <p class="text-gray-600 mb-6">O teu plano foi atualizado e já tens acesso a todos os recursos.</p>
                <a href="{{ route('dashboard') }}" class="bg-indigo-600 text-white px-4 py-2 rounded font-bold hover:bg-indigo-700">
                    Ir para o Dashboard
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
