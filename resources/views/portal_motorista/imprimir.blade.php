<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Embarque - Viagem #{{ $viagem->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
                padding: 0 !important;
            }

            .print-card {
                border: none !important;
                shadow: none !important;
                padding: 0 !important;
            }
        }
    </style>
</head>

<body class="bg-slate-100 text-slate-800 font-sans p-4 sm:p-8">

    <!-- Ações superiores (Oculto na impressão) -->
    <div class="max-w-4xl mx-auto mb-6 flex justify-between items-center no-print">
        <a href="{{ route('portal_motorista.show', $viagem->id) }}" class="inline-flex items-center gap-1 text-xs font-bold text-slate-600 hover:text-slate-900 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Voltar para a viagem</span>
        </a>
        <button onclick="window.print()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl shadow-sm transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            <span>Imprimir Ficha</span>
        </button>
    </div>

    <!-- Folha Imprimível -->
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl border border-slate-200 shadow-sm print-card space-y-6">

        <!-- Cabeçalho -->
        <div class="border-b border-slate-200 pb-4 flex justify-between items-start">
            <div>
                <h1 class="text-lg font-bold text-slate-900 uppercase tracking-wide">Relatório de Embarque de Passageiros</h1>
                <p class="text-xs text-slate-500 mt-0.5">Emissão: {{ now()->format('d/m/Y H:i') }}</p>
            </div>
            <div class="text-right">
                <span class="text-xs font-bold uppercase tracking-wider px-3 py-1 bg-slate-100 text-slate-700 rounded-lg border border-slate-200">
                    Viagem #{{ $viagem->id }}
                </span>
            </div>
        </div>

        <!-- Informações da Viagem -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs border-b border-slate-100 pb-4">
            <div>
                <span class="text-slate-400 block font-semibold">Rota:</span>
                <strong class="text-slate-800">{{ $viagem->rota->descricao ?? 'Não informada' }}</strong>
            </div>
            <div>
                <span class="text-slate-400 block font-semibold">Data/Hora Saída:</span>
                <strong class="text-slate-800">{{ optional($viagem->data_hora_saida)->format('d/m/Y H:i') ?? \Carbon\Carbon::parse($viagem->data_hora_saida)->format('d/m/Y H:i') }}</strong>
            </div>
            <div>
                <span class="text-slate-400 block font-semibold">Veículo / Placa:</span>
                <strong class="text-slate-800">{{ $viagem->veiculo->placa ?? '-' }}</strong>
            </div>
            <div>
                <span class="text-slate-400 block font-semibold">Total Passageiros:</span>
                <strong class="text-slate-800">{{ $viagem->passageiros->count() }}</strong>
            </div>
        </div>

        <!-- Tabela da Chamada -->
        <div>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b-2 border-slate-300 text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                        <th class="py-2.5 px-2 w-8">#</th>
                        <th class="py-2.5 px-2">Passageiro</th>
                        <th class="py-2.5 px-2">Ponto de Subida</th>
                        <th class="py-2.5 px-2">Ponto de Descida</th>
                        <th class="py-2.5 px-2 w-28 text-center">Presença</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-xs text-slate-800">
                    @forelse($viagem->passageiros as $index => $passageiro)
                    <tr>
                        <td class="py-3 px-2 font-semibold text-slate-400">{{ $index + 1 }}</td>
                        <td class="py-3 px-2 font-bold text-slate-900">{{ $passageiro->name }}</td>
                        <td class="py-3 px-2">
                            {{ \App\Models\PontoDeParada::find($passageiro->pivot->ponto_de_parada_saida_id)?->descricao ?? '-' }}
                        </td>
                        <td class="py-3 px-2">
                            {{ \App\Models\PontoDeParada::find($passageiro->pivot->ponto_de_parada_chegada_id)?->descricao ?? '-' }}
                        </td>
                        <td class="py-3 px-2 text-center">
                            @if(!empty($passageiro->pivot->data_hora_saida))
                            <!-- Presença Confirmada (Check verde) -->
                            <div class="w-6 h-6 bg-emerald-500 text-white rounded-md mx-auto flex items-center justify-center shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            @else
                            <!-- Presença Pendente (Caixa vazia) -->
                            <div class="w-6 h-6 border-2 border-slate-300 rounded-md mx-auto bg-slate-50"></div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-400">
                            Nenhum passageiro cadastrado nesta viagem.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Seção de Assinaturas -->
        <div class="pt-16 grid grid-cols-2 gap-12 text-center text-xs text-slate-500">
            <div>
                <div class="border-t border-slate-300 pt-2 font-semibold text-slate-700">Assinatura do Motorista</div>
            </div>
            <div>
                <div class="border-t border-slate-300 pt-2 font-semibold text-slate-700">Visto / Fiscalização</div>
            </div>
        </div>

    </div>

</body>

</html>