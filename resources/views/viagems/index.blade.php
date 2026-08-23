<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Viagens</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rota</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Motorista ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Veículo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Saída</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($viagens as $viagem)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $viagem->rota->descricao ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $viagem->motorista_id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $viagem->veiculo->placa ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $viagem->data_hora_saida->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('viagens.qrcode', $viagem->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold">Ver QR Code</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>