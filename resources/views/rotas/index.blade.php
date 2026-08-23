<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Rotas e Paradas</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rota</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pontos de Parada (Ordenados)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($rotas as $rota)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-bold">{{ $rota->descricao }}</td>
                                <td class="px-6 py-4">
                                    <ol class="list-decimal list-inside text-sm text-gray-700">
                                        @foreach($rota->pontosDeParada as $ponto)
                                            <li>{{ $ponto->descricao }}</li>
                                        @endforeach
                                    </ol>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $rota->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $rota->ativo ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>