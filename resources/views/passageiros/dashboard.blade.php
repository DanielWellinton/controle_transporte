<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Painel do Passageiro - Viagens Ativas
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-red-100 border border-red-200 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Leitor de QR Code Rápido -->
            <div class="bg-indigo-900 text-white p-6 rounded-xl shadow-md flex flex-col md:flex-row items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold">Já está no veículo?</h3>
                    <p class="text-sm text-indigo-200">Escaneie o QR Code no veículo para confirmar seu embarque.</p>
                </div>
            </div>

            <!-- Minhas Reservas Ativas -->
            @if($minhasViagens->count() > 0)
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Minhas Reservas</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($minhasViagens as $reserva)
                            <div class="border rounded-xl p-5 bg-gray-50 flex flex-col justify-between space-y-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-bold text-gray-900 text-base">{{ $reserva->viagem->rota->descricao ?? 'Viagem' }}</h4>
                                        <p class="text-xs text-gray-500">Motorista: {{ $reserva->viagem->motorista->usuario->name ?? 'N/A' }}</p>
                                    </div>
                                    @if($reserva->status === 'presente')
                                        <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full border border-green-300">
                                            Embarcado ({{ $reserva->data_hora_saida->format('H:i') }})
                                        </span>
                                    @else
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1 rounded-full border border-yellow-300">
                                            Aguardando QR Code
                                        </span>
                                    @endif
                                </div>

                                <!-- Formulário para alterar os pontos caso não tenha embarcado -->
                                @if($reserva->status !== 'presente')
                                    <form action="{{ route('passageiros.selecionar-pontos', $reserva->viagem_id) }}" method="POST" class="space-y-3 bg-white p-3 rounded-lg border">
                                        @csrf
                                        <p class="text-xs font-bold text-gray-700">Alterar meus pontos de parada:</p>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                            <div>
                                                <label class="block text-xs text-gray-500">Embarque</label>
                                                <select name="ponto_de_parada_saida_id" required class="mt-1 block w-full text-xs rounded-md border-gray-300">
                                                    @foreach($reserva->viagem->rota->pontosDeParada as $ponto)
                                                        <option value="{{ $ponto->id }}" {{ $reserva->ponto_de_parada_saida_id == $ponto->id ? 'selected' : '' }}>
                                                            {{ $ponto->pivot->ordem }}º - {{ $ponto->descricao }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div>
                                                <label class="block text-xs text-gray-500">Desembarque</label>
                                                <select name="ponto_de_parada_chegada_id" required class="mt-1 block w-full text-xs rounded-md border-gray-300">
                                                    @foreach($reserva->viagem->rota->pontosDeParada as $ponto)
                                                        <option value="{{ $ponto->id }}" {{ $reserva->ponto_de_parada_chegada_id == $ponto->id ? 'selected' : '' }}>
                                                            {{ $ponto->pivot->ordem }}º - {{ $ponto->descricao }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="flex justify-between items-center pt-2">
                                            <button type="submit" class="px-3 py-1.5 bg-gray-800 text-white text-xs font-semibold rounded hover:bg-gray-700">
                                                Salvar Alterações
                                            </button>
                                        </div>
                                    </form>

                                    <!-- Botão de Desistência -->
                                    <form action="{{ route('passageiros.cancelar-reserva', $reserva->viagem_id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja desistir desta viagem?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full py-2 bg-red-50 text-red-600 border border-red-200 text-xs font-bold rounded-lg hover:bg-red-100 transition">
                                            🚫 Desistir / Cancelar Reserva
                                        </button>
                                    </form>
                                @else
                                    <div class="text-xs text-gray-600 bg-gray-100 p-3 rounded-lg">
                                        <p><strong>Embarque:</strong> {{ $reserva->pontoSaida->descricao ?? 'N/A' }}</p>
                                        <p><strong>Desembarque:</strong> {{ $reserva->pontoChegada->descricao ?? 'N/A' }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Listagem de Viagens Disponíveis (que o passageiro ainda não reservou) -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Outras Viagens Ativas</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @php
                        $reservasIds = $minhasViagens->pluck('viagem_id')->toArray();
                    @endphp

                    @forelse($viagensAtivas->whereNotIn('id', $reservasIds) as $viagem)
                        <div class="border rounded-xl p-5 space-y-4 hover:border-indigo-300 transition">
                            <div>
                                <h4 class="font-bold text-gray-900 text-lg">{{ $viagem->rota->descricao ?? 'Rota sem nome' }}</h4>
                                <p class="text-xs text-gray-500">Motorista: {{ $viagem->motorista->usuario->name ?? 'N/A' }} | Veículo: {{ $viagem->veiculo->placa ?? '' }}</p>
                            </div>

                            <form action="{{ route('passageiros.selecionar-pontos', $viagem) }}" method="POST" class="space-y-3">
                                @csrf
                                <div>
                                    <label class="block text-xs font-semibold uppercase text-gray-500">Ponto de Embarque</label>
                                    <select name="ponto_de_parada_saida_id" required class="mt-1 block w-full text-sm rounded-md border-gray-300">
                                        <option value="">Escolha onde vai subir...</option>
                                        @foreach($viagem->rota->pontosDeParada as $ponto)
                                            <option value="{{ $ponto->id }}">{{ $ponto->pivot->ordem }}º - {{ $ponto->descricao }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase text-gray-500">Ponto de Desembarque</label>
                                    <select name="ponto_de_parada_chegada_id" required class="mt-1 block w-full text-sm rounded-md border-gray-300">
                                        <option value="">Escolha onde vai descer...</option>
                                        @foreach($viagem->rota->pontosDeParada as $ponto)
                                            <option value="{{ $ponto->id }}">{{ $ponto->pivot->ordem }}º - {{ $ponto->descricao }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-md transition">
                                    Confirmar Embarque Nesta Viagem
                                </button>
                            </form>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 col-span-2">Nenhuma outra viagem disponível para reserva no momento.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>