<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Criar Novo Plano') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100">
                <form action="{{ route('admin.plans.store') }}" method="POST">
                    @csrf

                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Nome do Plano -->
                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-300">Nome do Plano</label>
                        <input type="text" name="name" value="{{ old('name', $plan->name ?? '') }}" required class="mt-1 block w-full rounded-md bg-gray-900 border-gray-700 text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('name') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Slug -->
                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-300">Slug (ex: free, pro, equipe)</label>
                        <input type="text" name="slug" value="{{ old('slug', $plan->slug ?? '') }}" required class="mt-1 block w-full rounded-md bg-gray-900 border-gray-700 text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('slug') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-300">Preço Mensal (R$)</label>
                        <input type="number" step="0.01" name="price_monthly" value="{{ old('price_monthly', $plan->price_monthly ?? '') }}" required class="mt-1 block w-full rounded-md bg-gray-900 border-gray-700 text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('price_monthly') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-300">Preço Anual (R$)</label>
                        <input type="number" step="0.01" name="price_yearly" value="{{ old('price_yearly', $plan->price_yearly ?? '') }}" required class="mt-1 block w-full rounded-md bg-gray-900 border-gray-700 text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('price_yearly') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('admin.plans.index') }}" class="text-gray-400 hover:text-gray-200 mr-4 text-sm">Cancelar</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-semibold">Guardar Plano</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
