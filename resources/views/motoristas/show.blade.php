<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalhes do Motorista') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-semibold text-gray-500">Nome:</p>
                            <p class="text-lg">{{ $motorista->usuario->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-500">E-mail:</p>
                            <p class="text-lg">{{ $motorista->usuario->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-500">CNH:</p>
                            <p class="text-lg">{{ $motorista->cnh }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-500">Validade da CNH:</p>
                            <p class="text-lg">{{ \Carbon\Carbon::parse($motorista->data_validade_cnh)->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-500">Status:</p>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $motorista->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $motorista->ativo ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('motoristas.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Voltar</a>
                        <a href="{{ route('motoristas.edit', $motorista) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-sm">
                            Editar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>