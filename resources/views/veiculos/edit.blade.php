<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Veículo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('veiculos.update', $veiculo) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Descrição -->
                        <div class="mb-4">
                            <x-input-label for="descricao" :value="__('Descrição')" />
                            <x-text-input id="descricao" class="block mt-1 w-full" type="text" name="descricao" :value="old('descricao', $veiculo->descricao)" required />
                            <x-input-error :messages="$errors->get('descricao')" class="mt-2" />
                        </div>

                        <!-- Placa -->
                        <div class="mb-4">
                            <x-input-label for="placa" :value="__('Placa')" />
                            <x-text-input id="placa" class="block mt-1 w-full uppercase" type="text" name="placa" :value="old('placa', $veiculo->placa)" required />
                            <x-input-error :messages="$errors->get('placa')" class="mt-2" />
                        </div>

                        <!-- Número de Passageiros -->
                        <div class="mb-4">
                            <x-input-label for="numero_passageiros" :value="__('Número de Passageiros')" />
                            <x-text-input id="numero_passageiros" class="block mt-1 w-full" type="number" name="numero_passageiros" :value="old('numero_passageiros', $veiculo->numero_passageiros)" required min="1" />
                            <x-input-error :messages="$errors->get('numero_passageiros')" class="mt-2" />
                        </div>

                        <!-- Ativo -->
                        <div class="mb-4">
                            <label for="ativo" class="inline-flex items-center">
                                <input 
                                    id="ativo" 
                                    type="checkbox" 
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" 
                                    name="ativo" 
                                    value="1" 
                                    {{ old('ativo', $veiculo->ativo) ? 'checked' : '' }}
                                >
                                <span class="ms-2 text-sm text-gray-600">{{ __('Veículo Ativo') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end mt-6 gap-3">
                            <a href="{{ route('veiculos.index') }}" class="text-gray-600 hover:text-gray-900 text-sm font-semibold">Cancelar</a>
                            <x-primary-button>
                                {{ __('Atualizar') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>