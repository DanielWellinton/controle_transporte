<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Motorista') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('motoristas.update', $motorista) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Usuário -->
                        <div class="mb-4">
                            <x-input-label for="usuario_id" :value="__('Usuário do Sistema')" />
                            <select id="usuario_id" name="usuario_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1">
                                @foreach ($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}" {{ old('usuario_id', $motorista->usuario_id) == $usuario->id ? 'selected' : '' }}>
                                        {{ $usuario->name }} ({{ $usuario->email }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('usuario_id')" class="mt-2" />
                        </div>

                        <!-- CNH -->
                        <div class="mb-4">
                            <x-input-label for="cnh" :value="__('Número da CNH')" />
                            <x-text-input id="cnh" class="block mt-1 w-full" type="text" name="cnh" :value="old('cnh', $motorista->cnh)" required />
                            <x-input-error :messages="$errors->get('cnh')" class="mt-2" />
                        </div>

                        <!-- Validade CNH (Formatada estritamente como YYYY-MM-DD para o input de data) -->
                        <div class="mb-4">
                            <x-input-label for="data_validade_cnh" :value="__('Data de Validade da CNH')" />
                            <x-text-input 
                                id="data_validade_cnh" 
                                class="block mt-1 w-full" 
                                type="date" 
                                name="data_validade_cnh" 
                                :value="old('data_validade_cnh', \Carbon\Carbon::parse($motorista->data_validade_cnh)->format('Y-m-d'))" 
                                required 
                            />
                            <x-input-error :messages="$errors->get('data_validade_cnh')" class="mt-2" />
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
                                    {{ old('ativo', $motorista->ativo) ? 'checked' : '' }}
                                >
                                <span class="ms-2 text-sm text-gray-600">{{ __('Motorista Ativo') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('motoristas.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Cancelar</a>
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