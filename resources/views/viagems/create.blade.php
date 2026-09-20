@extends('layouts.admin')

@section('title', isset($viagem) ? 'Duplicar Viagem' : 'Agendar Viagem')
@section('header_title', isset($viagem) ? 'Duplicar Viagem Existente' : 'Agendar Nova Viagem')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Card de Cabeçalho / Ações -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('viagems.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
                    &larr; Voltar para Viagens
                </a>
            </div>
            <h2 class="text-base font-bold text-slate-800 mt-1">
                {{ isset($viagem) ? 'Duplicar Viagem' : 'Agendar Nova Viagem' }}
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">
                {{ isset($viagem) ? 'Ajuste os dados conforme necessário para criar a nova viagem com base na selecionada.' : 'Preencha os dados operacionais para cadastrar o manifesto da viagem.' }}
            </p>
        </div>
    </div>

    <!-- Formulário Principal -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('viagems.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Autocomplete: Rota -->
            <div class="relative autocomplete-container" 
                 data-url="{{ route('rotas.autocomplete') }}"
                 data-label-key="descricao">
                <label for="search_rota" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                    Rota <span class="text-rose-500">*</span>
                </label>
                
                <input type="hidden" name="rota_id" class="autocomplete-id" 
                       value="{{ old('rota_id', $viagem->rota_id ?? '') }}" required>

                <div class="relative">
                    <input type="text" id="search_rota" class="autocomplete-search w-full text-xs font-medium text-slate-800 bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-3 pr-10 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition @error('rota_id') border-rose-500 @enderror"
                        placeholder="Digite para buscar a rota..."
                        value="{{ old('rota_nome', $viagem->rota->descricao ?? '') }}"
                        autocomplete="off">

                    <button type="button" class="autocomplete-clear hidden absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1">
                        &times;
                    </button>
                </div>

                <!-- Lista de Resultados -->
                <div class="autocomplete-results hidden absolute z-30 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-lg max-h-56 overflow-y-auto divide-y divide-slate-100"></div>

                @error('rota_id')
                    <p class="text-xs font-medium text-rose-500 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Grid: Motorista e Veículo -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Autocomplete: Motorista -->
                <div class="relative autocomplete-container" 
                     data-url="{{ route('motoristas.autocomplete') }}"
                     data-label-key="nome">
                    <label for="search_motorista" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                        Motorista <span class="text-rose-500">*</span>
                    </label>

                    <input type="hidden" name="motorista_id" class="autocomplete-id" 
                           value="{{ old('motorista_id', $viagem->motorista_id ?? '') }}" required>

                    <div class="relative">
                        <input type="text" id="search_motorista" class="autocomplete-search w-full text-xs font-medium text-slate-800 bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-3 pr-10 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition @error('motorista_id') border-rose-500 @enderror"
                            placeholder="Digite o nome do motorista..."
                            value="{{ old('motorista_nome', $viagem->motorista->usuario->name ?? '') }}"
                            autocomplete="off">

                        <button type="button" class="autocomplete-clear hidden absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1">
                            &times;
                        </button>
                    </div>

                    <div class="autocomplete-results hidden absolute z-30 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-lg max-h-56 overflow-y-auto divide-y divide-slate-100"></div>

                    @error('motorista_id')
                        <p class="text-xs font-medium text-rose-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Autocomplete: Veículo -->
                <div class="relative autocomplete-container" 
                     data-url="{{ route('veiculos.autocomplete') }}"
                     data-label-key="descricao"
                     data-sublabel-key="placa">
                    <label for="search_veiculo" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                        Veículo <span class="text-rose-500">*</span>
                    </label>

                    <input type="hidden" name="veiculo_id" class="autocomplete-id" 
                           value="{{ old('veiculo_id', $viagem->veiculo_id ?? '') }}" required>

                    <div class="relative">
                        <input type="text" id="search_veiculo" class="autocomplete-search w-full text-xs font-medium text-slate-800 bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-3 pr-10 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition @error('veiculo_id') border-rose-500 @enderror"
                            placeholder="Buscar por nome ou placa..."
                            value="{{ old('veiculo_nome', isset($viagem->veiculo) ? $viagem->veiculo->descricao . ' (' .$viagem->veiculo->placa . ')' : '') }}"
                            autocomplete="off">

                        <button type="button" class="autocomplete-clear hidden absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1">
                            &times;
                        </button>
                    </div>

                    <div class="autocomplete-results hidden absolute z-30 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-lg max-h-56 overflow-y-auto divide-y divide-slate-100"></div>

                    @error('veiculo_id')
                        <p class="text-xs font-medium text-rose-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Grid: Saída e Chegada -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Data/Hora Saída -->
                <div>
                    <label for="data_hora_saida" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                        Data e Hora de Saída <span class="text-rose-500">*</span>
                    </label>
                    @php
                        $valSaida = old('data_hora_saida');
                        if (!$valSaida && isset($viagem->data_hora_saida)) {
                            $valSaida =$viagem->data_hora_saida instanceof \Carbon\Carbon
                                ? $viagem->data_hora_saida->format('Y-m-d\TH:i')                                 : \Carbon\Carbon::parse($viagem->data_hora_saida)->format('Y-m-d\TH:i');
                        }
                    @endphp
                    <input type="datetime-local" id="data_hora_saida" name="data_hora_saida" 
                        value="{{ $valSaida }}" required
                        class="w-full text-xs font-medium text-slate-800 bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-3 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition @error('data_hora_saida') border-rose-500 @enderror" />
                    @error('data_hora_saida')
                        <p class="text-xs font-medium text-rose-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Data/Hora Chegada -->
                <div>
                    <label for="data_hora_chegada" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                        Data e Hora de Chegada (Prevista)
                    </label>
                    @php
                        $valChegada = old('data_hora_chegada');
                        if (!$valChegada && isset($viagem->data_hora_chegada)) {
                            $valChegada =$viagem->data_hora_chegada instanceof \Carbon\Carbon
                                ? $viagem->data_hora_chegada->format('Y-m-d\TH:i')                                 : \Carbon\Carbon::parse($viagem->data_hora_chegada)->format('Y-m-d\TH:i');
                        }
                    @endphp
                    <input type="datetime-local" id="data_hora_chegada" name="data_hora_chegada" 
                        value="{{ $valChegada }}"
                        class="w-full text-xs font-medium text-slate-800 bg-slate-50/50 border border-slate-200 rounded-xl px-4 py-3 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition @error('data_hora_chegada') border-rose-500 @enderror" />
                    @error('data_hora_chegada')
                        <p class="text-xs font-medium text-rose-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Status Ativo/Inativo -->
            <div class="pt-2">
                <label class="inline-flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" id="ativo" name="ativo" value="1" 
                        {{ old('ativo', $viagem->ativo ?? true) ? 'checked' : '' }}
                        class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500/20 focus:ring-2 transition cursor-pointer">
                    <span class="text-xs font-medium text-slate-700 group-hover:text-slate-900 transition">
                        Viagem Ativa (Disponível para embarque / check-in)
                    </span>
                </label>
            </div>

            <!-- Botões de Ação -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('viagems.index') }}" 
                    class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition">
                    Cancelar
                </a>
                <button type="submit" 
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl transition shadow-md shadow-indigo-500/20">
                    Salvar Viagem
                </button>
            </div>
        </form>
    </div>

</div>

<!-- Script Vanilla Reutilizável de Autocomplete -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const containers = document.querySelectorAll('.autocomplete-container');

    containers.forEach(container => {
        const url = container.dataset.url;
        const labelKey = container.dataset.labelKey;
        const sublabelKey = container.dataset.sublabelKey;

        const searchInput = container.querySelector('.autocomplete-search');
        const hiddenIdInput = container.querySelector('.autocomplete-id');
        const resultsContainer = container.querySelector('.autocomplete-results');
        const clearBtn = container.querySelector('.autocomplete-clear');

        let debounceTimer = null;

        // Mostra botão de limpar se houver valor inicial
        if (searchInput.value.trim() !== '') {
            clearBtn.classList.remove('hidden');
        }

        searchInput.addEventListener('input', function () {
            const query = this.value.trim();
            hiddenIdInput.value = ''; // Limpa o ID até selecionar da lista

            if (query.length > 0) {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
                resultsContainer.classList.add('hidden');
                return;
            }

            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                fetch(`${url}?q=${encodeURIComponent(query)}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    resultsContainer.innerHTML = '';

                    if (!Array.isArray(data) || data.length === 0) {
                        resultsContainer.innerHTML = `
                            <div class="p-3 text-xs text-slate-400 text-center">
                                Nenhum resultado encontrado.
                            </div>`;
                    } else {
                        data.forEach(item => {
                            const option = document.createElement('div');
                            option.className = 'p-3 hover:bg-indigo-50 cursor-pointer transition flex flex-col gap-0.5 text-xs text-slate-700';

                            const primaryText = item[labelKey] ?? '';
                            const secondaryText = sublabelKey && item[sublabelKey] ? ` (${item[sublabelKey]})` : '';

                            option.innerHTML = `
                                <span class="font-bold text-slate-800">${primaryText}${secondaryText}</span>
                            `;

                            option.addEventListener('click', function () {
                                searchInput.value = `${primaryText}${secondaryText}`;
                                hiddenIdInput.value = item.id;
                                resultsContainer.classList.add('hidden');
                            });

                            resultsContainer.appendChild(option);
                        });
                    }

                    resultsContainer.classList.remove('hidden');
                })
                .catch(err => console.error('Erro na requisição de autocomplete:', err));
            }, 300);
        });

        // Limpar seleção ao clicar no 'X'
        clearBtn.addEventListener('click', function () {
            searchInput.value = '';
            hiddenIdInput.value = '';
            resultsContainer.classList.add('hidden');
            clearBtn.classList.add('hidden');
            searchInput.focus();
        });

        // Fechar a lista caso o usuário clique fora do campo
        document.addEventListener('click', function (e) {
            if (!container.contains(e.target)) {
                resultsContainer.classList.add('hidden');
            }
        });
    });
});
</script>
@endsection