@extends('layouts.admin')

@section('title', 'Editar Viagem')
@section('header_title', 'Editar Viagem')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('viagems.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
                    &larr; Voltar para Viagens
                </a>
                <h2 class="text-base font-bold text-slate-800">
                    Editar Viagem #{{ $viagem->id }}
                </h2>
            </div>
            <p class="text-xs text-slate-500 mt-1">Atualize a rota, alocação de motorista, veículo e horários previstos.</p>
        </div>

        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $viagem->ativo ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }} self-start sm:self-auto">
            <span class="w-1.5 h-1.5 rounded-full {{ $viagem->ativo ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
            {{ $viagem->ativo ? 'Viagem Ativa' : 'Viagem Inativa' }}
        </span>
    </div>

    @if ($errors->any())
        <div class="p-4 rounded-xl text-xs font-semibold text-rose-800 bg-rose-50 border border-rose-200 space-y-1">
            <p class="font-bold text-sm">Atenção:</p>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <form action="{{ route('viagems.update', $viagem) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Autocomplete: Rota -->
            <div class="relative autocomplete-container" 
                 data-url="{{ route('rotas.autocomplete') }}"
                 data-current-id="{{ old('rota_id', $viagem->rota_id) }}"
                 data-label-key="descricao">
                <label for="search_rota" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Rota <span class="text-rose-500">*</span>
                </label>
                
                <input type="hidden" name="rota_id" class="autocomplete-id" 
                       value="{{ old('rota_id', $viagem->rota_id) }}" required>

                <div class="relative">
                    <input type="text" id="search_rota" class="autocomplete-search w-full rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 text-sm focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm px-3 py-2.5 pr-10 @error('rota_id') border-rose-500 @enderror"
                        placeholder="Digite para buscar a rota..."
                        value="{{ old('rota_nome', $viagem->rota->descricao ?? '') }}"
                        autocomplete="off">

                    <button type="button" class="autocomplete-clear hidden absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1">
                        &times;
                    </button>
                </div>

                <div class="autocomplete-results hidden absolute z-30 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-lg max-h-56 overflow-y-auto divide-y divide-slate-100"></div>

                @error('rota_id')
                    <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Autocomplete: Motorista -->
                <div class="relative autocomplete-container" 
                     data-url="{{ route('motoristas.autocomplete') }}"
                     data-current-id="{{ old('motorista_id', $viagem->motorista_id) }}"
                     data-label-key="nome">
                    <label for="search_motorista" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Motorista <span class="text-rose-500">*</span>
                    </label>

                    <input type="hidden" name="motorista_id" class="autocomplete-id" 
                           value="{{ old('motorista_id', $viagem->motorista_id) }}" required>

                    <div class="relative">
                        <input type="text" id="search_motorista" class="autocomplete-search w-full rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 text-sm focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm px-3 py-2.5 pr-10 @error('motorista_id') border-rose-500 @enderror"
                            placeholder="Digite o nome do motorista..."
                            value="{{ old('motorista_nome', $viagem->motorista->usuario->name ?? $viagem->motorista->nome ?? '') }}"
                            autocomplete="off">

                        <button type="button" class="autocomplete-clear hidden absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1">
                            &times;
                        </button>
                    </div>

                    <div class="autocomplete-results hidden absolute z-30 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-lg max-h-56 overflow-y-auto divide-y divide-slate-100"></div>

                    @error('motorista_id')
                        <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Autocomplete: Veículo -->
                <div class="relative autocomplete-container" 
                     data-url="{{ route('veiculos.autocomplete') }}"
                     data-current-id="{{ old('veiculo_id', $viagem->veiculo_id) }}"
                     data-label-key="descricao"
                     data-sublabel-key="placa">
                    <label for="search_veiculo" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Veículo <span class="text-rose-500">*</span>
                    </label>

                    <input type="hidden" name="veiculo_id" class="autocomplete-id" 
                           value="{{ old('veiculo_id', $viagem->veiculo_id) }}" required>

                    <div class="relative">
                        <input type="text" id="search_veiculo" class="autocomplete-search w-full rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 text-sm focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm px-3 py-2.5 pr-10 @error('veiculo_id') border-rose-500 @enderror"
                            placeholder="Buscar por nome ou placa..."
                            value="{{ old('veiculo_nome', isset($viagem->veiculo) ? $viagem->veiculo->descricao . ' (' .$viagem->veiculo->placa . ')' : '') }}"
                            autocomplete="off">

                        <button type="button" class="autocomplete-clear hidden absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1">
                            &times;
                        </button>
                    </div>

                    <div class="autocomplete-results hidden absolute z-30 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-lg max-h-56 overflow-y-auto divide-y divide-slate-100"></div>

                    @error('veiculo_id')
                        <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Data / Hora de Saída -->
                <div>
                    <label for="data_hora_saida" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Data / Hora de Saída</label>
                    <input id="data_hora_saida" type="datetime-local" name="data_hora_saida"
                        value="{{ old('data_hora_saida', $viagem->data_hora_saida ? $viagem->data_hora_saida->format('Y-m-d\TH:i') : '') }}" required
                        class="w-full rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 text-sm focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm px-3 py-2.5">
                    @error('data_hora_saida')
                        <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Data / Hora de Chegada -->
                <div>
                    <label for="data_hora_chegada" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Data / Hora de Chegada</label>
                    <input id="data_hora_chegada" type="datetime-local" name="data_hora_chegada"
                        value="{{ old('data_hora_chegada', $viagem->data_hora_chegada ? $viagem->data_hora_chegada->format('Y-m-d\TH:i') : '') }}"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 text-sm focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm px-3 py-2.5">
                    @error('data_hora_chegada')
                        <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Ativo -->
            <div class="pt-2">
                <label for="ativo" class="inline-flex items-center gap-2 cursor-pointer select-none">
                    <input id="ativo" type="checkbox" name="ativo" value="1" {{ old('ativo', $viagem->ativo) ? 'checked' : '' }}
                        class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500 cursor-pointer w-4 h-4">
                    <span class="text-xs font-bold text-slate-700">Viagem Ativa</span>
                </label>
            </div>

            <!-- Botões de Ação -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('viagems.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition">
                    Cancelar
                </a>
                <button type="submit"
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl transition shadow-md shadow-indigo-500/20 flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Atualizar Viagem</span>
                </button>
            </div>
        </form>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const containers = document.querySelectorAll('.autocomplete-container');

    containers.forEach(container => {
        const baseUrl = container.dataset.url;
        const currentId = container.dataset.currentId || '';
        const labelKey = container.dataset.labelKey;
        const sublabelKey = container.dataset.sublabelKey;

        const searchInput = container.querySelector('.autocomplete-search');
        const hiddenIdInput = container.querySelector('.autocomplete-id');
        const resultsContainer = container.querySelector('.autocomplete-results');
        const clearBtn = container.querySelector('.autocomplete-clear');

        let debounceTimer = null;

        if (searchInput.value.trim() !== '') {
            clearBtn.classList.remove('hidden');
        }

        function executarBusca() {
            const query = searchInput.value.trim();

            if (query.length === 0) {
                hiddenIdInput.value = '';
                clearBtn.classList.add('hidden');
                resultsContainer.classList.add('hidden');
                return;
            }

            clearBtn.classList.remove('hidden');

            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const fetchUrl = `${baseUrl}?q=${encodeURIComponent(query)}&current_id=${encodeURIComponent(currentId)}`;

                fetch(fetchUrl, {
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
                .catch(err => console.error('Erro na busca de autocomplete:', err));
            }, 300);
        }

        searchInput.addEventListener('input', executarBusca);

        clearBtn.addEventListener('click', function () {
            searchInput.value = '';
            hiddenIdInput.value = '';
            resultsContainer.classList.add('hidden');
            clearBtn.classList.add('hidden');
            searchInput.focus();
        });

        document.addEventListener('click', function (e) {
            if (!container.contains(e.target)) {
                resultsContainer.classList.add('hidden');
            }
        });
    });
});
</script>
@endsection