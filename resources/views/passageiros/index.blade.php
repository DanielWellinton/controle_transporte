<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Viagens Disponíveis') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alertas de Sucesso / Erro --}}
            @if (session('success'))
            <div class="mb-6 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-800 p-4 rounded-md shadow-sm">
                {{ session('success') }}
            </div>
            @endif

            @if (session('error'))
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow-sm">
                {{ session('error') }}
            </div>
            @endif

            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-700">Escolha uma viagem ativa</h3>
                <p class="text-sm text-gray-500">Selecione uma rota para definir seus pontos de embarque e desembarque.</p>
            </div>

            @if($viagens->isEmpty())
            <div class="bg-white rounded-xl shadow-sm p-8 text-center border border-gray-100">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma viagem ativa</h3>
                <p class="mt-1 text-sm text-gray-500">Não há viagens agendadas ou em andamento no momento.</p>
            </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($viagens as $viagem)
                @php
                // Busca a inscrição pelo id do usuário logado
                $meuEmbarque = $viagem->passageiros->where('id', auth()->id())->first();
                @endphp

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition flex flex-col justify-between">
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-2.5 py-0.5 text-xs font-bold rounded-full {{ $viagem->ativo ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $viagem->ativo ? 'Ativa' : 'Inativa' }}
                            </span>
                            @if($meuEmbarque)
                            <span class="text-[10px] bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded-full uppercase">
                                Já inscrito
                            </span>
                            @endif
                        </div>

                        <h4 class="text-base font-bold text-gray-800 mb-1">
                            {{ $viagem->rota->descricao ?? 'Rota não informada' }}
                        </h4>

                        <p class="text-xs text-gray-500 mb-4">
                            <strong>Saída:</strong> {{ \Carbon\Carbon::parse($viagem->data_hora_saida)->format('d/m/Y H:i') }}
                        </p>

                        <div class="space-y-2 border-t border-b border-gray-100 py-3 mb-4 text-xs text-gray-600">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>Motorista: <strong>{{ $viagem->motorista->usuario->name ?? 'A definir' }}</strong></span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                                <span>Veículo: <strong>{{ $viagem->veiculo->modelo ?? 'N/A' }} ({{ $viagem->veiculo->placa ?? '-' }})</strong></span>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 pt-0 mt-auto space-y-2">
                        @if($meuEmbarque)
                        {{-- Caso já esteja inscrito, exibe o botão para abrir o Leitor de QR Code --}}
                        <a href="{{ route('passageiros.scanner') }}"
                            class="w-full flex justify-center items-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-bold text-xs rounded-lg uppercase tracking-wider shadow transition duration-150 ease-in-out text-center no-underline cursor-pointer"
                            style="background-color: #059669 !important; color: #ffffff !important;">
                            <svg class="w-4 h-4 mr-2 stroke-current" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                            <span>Confirmar Embarque (QR Code)</span>
                        </a>
                        @endif

                        {{-- Botão de selecionar/alterar pontos --}}
                        <a href="{{ route('passageiros.selecionar-pontos', $viagem->id) }}"
                            class="w-full flex justify-center items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-bold text-xs rounded-lg uppercase tracking-wider shadow transition duration-150 ease-in-out text-center no-underline cursor-pointer"
                            style="background-color: #4f46e5 !important; color: #ffffff !important;">
                            <span>{{ $meuEmbarque ? 'Alterar Meus Pontos' : 'Selecionar Viagem' }}</span>
                            <svg class="w-4 h-4 ml-2 stroke-current" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

        </div>
    </div>
</x-app-layout>