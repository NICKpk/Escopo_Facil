<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Gestão de Planos') }}
            </h2>
            <a href="{{ route('admin.plans.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-semibold">
                Novo Plano
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-700">
                            <th class="py-3 px-4">Nome</th>
                            <th class="py-3 px-4">Descrição</th>
                            <th class="py-3 px-4">Preço</th>
                            <th class="py-3 px-4 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($plans as $plan)
                            <tr class="border-b border-gray-700 hover:bg-gray-700/50">
                                <td class="py-3 px-4 font-medium">{{ $plan->name }}</td>
                                <td class="py-3 px-4 text-gray-400">{{ $plan->description ?? 'Sem descrição' }}</td>
                                <td class="py-3 px-4">R$ {{ number_format($plan->price, 2, ',', '.') }}</td>
                                <td class="py-3 px-4 text-right space-x-2">
                                    <a href="{{ route('admin.plans.edit', $plan->id) }}" class="text-yellow-400 hover:underline">Editar</a>
                                    <form action="{{ route('admin.plans.destroy', $plan->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Tens a certeza que pretendes apagar este plano?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:underline">Apagar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-gray-400">Nenhum plano encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
