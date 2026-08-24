<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Agendar Viagem') }}
            </h2>
            <a href="{{ route('viagems.index') }}" class="text-gray-600 hover:text-gray-900 text-sm font-semibold">Voltar</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('viagems.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <x-input-label for="rota_id" :value="__('Rota')" />
                        <select id="rota_id" name="rota_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            <option value="">-- Selecione uma Rota --</option>
                            @foreach($rotas as $rota)
                            <option value="{{ $rota->id }}" {{ old('rota_id') == $rota->id ? 'selected' : '' }}>
                                {{ $rota->descricao }}
                            </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('rota_id')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="motorista_id" :value="__('Motorista')" />
                            <select id="motorista_id" name="motorista_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">-- Selecione um Motorista --</option>
                                @foreach($motoristas as $motorista)
                                <option value="{{ $motorista->id }}" {{ old('motorista_id') == $motorista->id ? 'selected' : '' }}>
                                    {{ $motorista->usuario->name }}
                                </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('motorista_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="veiculo_id" :value="__('Veículo')" />
                            <select id="veiculo_id" name="veiculo_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">-- Selecione um Veículo --</option>
                                @foreach($veiculos as $veiculo)
                                <option value="{{ $veiculo->id }}" {{ old('veiculo_id') == $veiculo->id ? 'selected' : '' }}>
                                    {{ $veiculo->modelo }} ({{ $veiculo->placa }})
                                </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('veiculo_id')" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="data_hora_saida" :value="__('Data / Hora de Saída')" />
                            <x-text-input id="data_hora_saida" class="block mt-1 w-full" type="datetime-local" name="data_hora_saida" :value="old('data_hora_saida')" required />
                            <x-input-error :messages="$errors->get('data_hora_saida')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="data_hora_chegada" :value="__('Data / Hora de Chegada (Prevista ou Real)')" />
                            <x-text-input id="data_hora_chegada" class="block mt-1 w-full" type="datetime-local" name="data_hora_chegada" :value="old('data_hora_chegada')" />
                            <x-input-error :messages="$errors->get('data_hora_chegada')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <label for="ativo" class="inline-flex items-center">
                            <input id="ativo" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="ativo" value="1" {{ old('ativo', true) ? 'checked' : '' }}>
                            <span class="ms-2 text-sm text-gray-600">{{ __('Viagem Ativa') }}</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end pt-4">
                        <x-primary-button>
                            {{ __('Salvar Viagem') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>