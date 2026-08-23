<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalhes da Rota') }}
            </h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('rotas.index') }}" class="text-gray-600 hover:text-gray-900 text-sm font-semibold">Voltar</a>
                <a href="{{ route('rotas.edit', $rota) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-sm transition-colors shadow-sm">
                    Editar Rota
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Informações Principais -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm font-semibold text-gray-500">Descrição:</p>
                        <p class="text-lg font-bold text-gray-900">{{ $rota->descricao }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-500">Status da Rota:</p>
                        <span class="mt-1 px-3 py-1 inline-flex text-xs font-semibold rounded-full {{ $rota->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $rota->ativo ? 'Ativa' : 'Inativa' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Listagem Apenas Leitura dos Pontos -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Pontos de Parada Vinculados</h3>

                @if($rota->pontosDeParada->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-20">Ordem</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Descrição do Ponto</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Latitude / Longitude</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status na Rota</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($rota->pontosDeParada as $ponto)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-indigo-600">
                                        {{ $ponto->pivot->ordem }}º
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $ponto->descricao }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-600 font-mono">
                                        {{ $ponto->latitude }}, {{ $ponto->longitude }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full {{ $ponto->pivot->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $ponto->pivot->ativo ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-sm text-gray-500 py-4">Nenhum ponto de parada vinculado a esta rota.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>