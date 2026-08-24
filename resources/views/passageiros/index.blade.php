<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Passageiros das Viagens
            </h2>
            <a href="{{ route('passageiros.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-indigo-700">
                + Novo Passageiro
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Usuário</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Viagem</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Saída</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Chegada</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($passageiros as $passageiro)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $passageiro->usuario->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500">{{ $passageiro->usuario->email ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $passageiro->viagem->rota->descricao ?? 'Viagem #'.$passageiro->viagem_id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <div>{{ $passageiro->pontoSaida->descricao ?? '-' }}</div>
                                    <div class="text-xs text-gray-400">{{ $passageiro->data_hora_saida ? $passageiro->data_hora_saida->format('d/m/Y H:i') : '' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <div>{{ $passageiro->pontoChegada->descricao ?? '-' }}</div>
                                    <div class="text-xs text-gray-400">{{ $passageiro->data_hora_chegada ? $passageiro->data_hora_chegada->format('d/m/Y H:i') : '' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <a href="{{ route('passageiros.edit', $passageiro) }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>
                                    <form action="{{ route('passageiros.destroy', $passageiro) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Remover passageiro?')" class="text-red-600 hover:text-red-900">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Nenhum passageiro cadastrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $passageiros->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>