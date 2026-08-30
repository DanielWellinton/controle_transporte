<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Painel do Passageiro - Viagens Ativas
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

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

            <!-- MINHAS RESERVAS ATIVAS -->
            @if($minhasViagens->count() > 0)
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Sua Viagem Agendada</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($minhasViagens as $minha)
                            <div class="border border-indigo-200 bg-indigo-50/50 p-4 rounded-lg flex justify-between items-center">
                                <div>
                                    <span class="text-xs font-bold uppercase text-indigo-600 bg-indigo-100 px-2 py-0.5 rounded">
                                        Status: {{ ucfirst($minha->status) }}
                                    </span>
                                    <h4 class="font-bold text-gray-800 mt-2">{{ $minha->viagem->rota->descricao ?? 'Viagem #'.$minha->viagem_id }}</h4>
                                    <p class="text-xs text-gray-600 mt-1">
                                        <strong>Embarque:</strong> {{ $minha->pontoSaida->descricao ?? 'Não definido' }}
                                    </p>
                                    <p class="text-xs text-gray-600">
                                        <strong>Desembarque:</strong> {{ $minha->pontoChegada->descricao ?? 'Não definido' }}
                                    </p>
                                </div>
                                <div class="flex flex-col space-y-2">
                                    <a href="{{ route('passageiros.viagem.exibir', $minha->viagem_id) }}" 
                                       class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded text-center">
                                        Alterar Pontos
                                    </a>
                                    @if($minha->status !== 'presente')
                                        <form action="{{ route('passageiros.cancelarReserva', $minha->viagem_id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Deseja realmente cancelar sua reserva?')" 
                                                    class="w-full px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded">
                                                Desistir
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- LISTA DE VIAGENS DISPONÍVEIS -->
            <div>
                <h3 class="text-lg font-bold text-gray-700 mb-4">Escolha uma Viagem para Embarcar</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($viagensAtivas as $viagem)
                        <!-- Link envelopando o card inteiro -->
                        <a href="{{ route('passageiros.viagem.exibir', $viagem->id) }}" 
                           class="block bg-white rounded-xl shadow-sm hover:shadow-md transition border-2 border-transparent hover:border-indigo-500 p-5 group">
                            
                            <div class="flex justify-between items-center mb-3">
                                <span class="px-2.5 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full uppercase">
                                    Em Aberto
                                </span>
                                <span class="text-xs font-semibold text-gray-500">
                                    {{ $viagem->veiculo->modelo ?? 'Veículo' }} - {{ $viagem->veiculo->placa ?? '' }}
                                </span>
                            </div>

                            <h4 class="text-base font-bold text-gray-900 group-hover:text-indigo-600 transition">
                                {{ $viagem->rota->descricao ?? 'Rota #'.$viagem->rota_id }}
                            </h4>

                            <p class="text-xs text-gray-500 mt-1">
                                👨‍✈️ Motorista: {{ $viagem->motorista->usuario->name ?? 'Não atribuído' }}
                            </p>

                            <div class="mt-4 pt-3 border-t border-gray-100 flex justify-between items-center">
                                <span class="text-xs font-medium text-gray-500">
                                    {{ $viagem->rota->pontosDeParada->count() }} pontos de parada
                                </span>
                                <span class="text-xs font-bold text-indigo-600 group-hover:translate-x-1 transition-transform flex items-center">
                                    Ver no Mapa &rarr;
                                </span>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full bg-white rounded-xl p-8 text-center text-gray-500 shadow-sm">
                            Nenhuma viagem ativa encontrada no momento.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>