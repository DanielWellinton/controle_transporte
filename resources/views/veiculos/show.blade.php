<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalhes do Veículo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-semibold text-gray-500">Descrição:</p>
                            <p class="text-lg font-bold text-gray-900">{{ $veiculo->descricao }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-500">Placa:</p>
                            <p class="text-lg uppercase font-bold text-gray-900">{{ $veiculo->placa }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-500">Capacidade de Passageiros:</p>
                            <p class="text-lg text-gray-900">{{ $veiculo->numero_passageiros }} assentos</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-500">Status:</p>
                            <span class="mt-1 px-3 py-1 inline-flex text-xs font-semibold rounded-full {{ $veiculo->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $veiculo->ativo ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 gap-3">
                        <a href="{{ route('veiculos.index') }}" class="text-gray-600 hover:text-gray-900 text-sm font-semibold">Voltar</a>
                        <a href="{{ route('veiculos.edit', $veiculo) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-sm transition-colors shadow-sm">
                            Editar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>