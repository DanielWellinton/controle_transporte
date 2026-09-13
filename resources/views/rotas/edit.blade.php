@extends('layouts.admin')

@section('title', 'Editar Rota')
@section('header_title', 'Editar Rota')

@section('content')
<!-- Leaflet CSS carregado diretamente -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    /* Previne distorções visuais de marcadores e tiles causadas por resets de imagem do Tailwind */
    .leaflet-container img {
        max-width: none !important;
        max-height: none !important;
    }
</style>

@php
    $pontosMapeados = $rota->pontosDeParada
        ->filter(function($ponto) {
            return (bool)$ponto->pivot->ativo;
        })
        ->map(function($ponto) {
            return [
                'id' => $ponto->id,
                'descricao' => $ponto->descricao,
                'lat' => (float) str_replace(',', '.', $ponto->latitude),
                'lng' => (float) str_replace(',', '.', $ponto->longitude),
                'ordem' => (int) $ponto->pivot->ordem
            ];
        })
        ->values();
@endphp

<div class="max-w-7xl mx-auto space-y-6">

    <!-- Card Topo / Cabeçalho -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('rotas.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
                    &larr; Voltar para Rotas
                </a>
                <h2 class="text-base font-bold text-slate-800">
                    Editar Rota: {{ $rota->descricao }}
                </h2>
            </div>
            <p class="text-xs text-slate-500 mt-1">Altere as informações básicas, vincule ou reordene os pontos de parada do percurso.</p>
        </div>

        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $rota->ativo ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }} self-start sm:self-auto">
            <span class="w-1.5 h-1.5 rounded-full {{ $rota->ativo ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
            {{ $rota->ativo ? 'Rota Ativa' : 'Rota Inativa' }}
        </span>
    </div>

    <!-- Mensagens de Sucesso -->
    @if(session('success'))
        <div class="flex items-center justify-between rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 shadow-sm">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-xs font-semibold">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Erros de Validação Geral -->
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

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- DADOS BÁSICOS E VINCULAR PONTO (COLUNA ESQUERDA - 5 COLUNAS) -->
        <div class="lg:col-span-5 space-y-6">

            <!-- Card: Dados Básicos -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Dados Básicos da Rota</h3>

                <form action="{{ route('rotas.update', $rota) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="descricao" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Descrição</label>
                        <input id="descricao" type="text" name="descricao" value="{{ old('descricao', $rota->descricao) }}" required
                            class="w-full rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 text-sm focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm px-3 py-2">
                        @error('descricao')
                            <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-1">
                        <label for="ativo" class="inline-flex items-center gap-2 cursor-pointer select-none">
                            <input id="ativo" type="checkbox" name="ativo" value="1" {{ old('ativo', $rota->ativo) ? 'checked' : '' }}
                                class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500 cursor-pointer w-4 h-4">
                            <span class="text-xs font-bold text-slate-700">Rota Ativa</span>
                        </label>
                    </div>

                    <button type="submit"
                        class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl transition shadow-md shadow-indigo-500/20 flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Atualizar Dados Básicos</span>
                    </button>
                </form>
            </div>

            <!-- Card: Vincular Novo Ponto -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Vincular Novo Ponto de Parada</h3>

                <form action="{{ route('rotas.pontos.store', $rota) }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="ponto_de_parada_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Selecione um Ponto</label>
                        <select id="ponto_de_parada_id" name="ponto_de_parada_id" required
                            class="w-full rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 text-sm focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm px-3 py-2">
                            <option value="">-- Escolha um ponto --</option>
                            @foreach($pontosDisponiveis as $ponto)
                                <option value="{{ $ponto->id }}">
                                    {{ $ponto->descricao }} (Lat: {{ $ponto->latitude }}, Long: {{ $ponto->longitude }})
                                </option>
                            @endforeach
                        </select>
                        @error('ponto_de_parada_id')
                            <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs rounded-xl transition shadow-md flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Vincular Ponto à Rota</span>
                    </button>
                </form>
            </div>

        </div>

        <!-- MAPA DO TRAJETO (COLUNA DIREITA - 7 COLUNAS) -->
        <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200 shadow-sm p-4 flex flex-col justify-between space-y-3">
            <div class="flex justify-between items-center px-1">
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Trajeto nas Rodovias / Ruas</h3>
                <div id="distancia-info" class="text-xs font-bold text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-lg border border-indigo-100 hidden"></div>
            </div>

            <div 
                id="map" 
                style="height: 440px; min-height: 440px; width: 100%;"
                class="rounded-xl border border-slate-200 z-10 overflow-hidden"
                data-pontos='@json($pontosMapeados)'
            ></div>
        </div>

    </div>

    <!-- GERENCIAR E REORDENAR PONTOS (LARGURA TOTAL ABAIXO) -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h3 class="text-base font-bold text-slate-800">Gerenciar Pontos da Rota</h3>
                <p class="text-xs text-slate-500 mt-0.5">Arraste as linhas para reordenar a sequência de paradas.</p>
            </div>

            <span class="text-[11px] font-semibold text-slate-500 bg-slate-100 px-3 py-1.5 rounded-xl border border-slate-200 flex items-center gap-1.5 self-start sm:self-auto">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11"></path></svg>
                Arraste o ícone de linhas para reordenar
            </span>
        </div>

        @if($rota->pontosDeParada->count() > 0)
            <!-- Formulário invisível apenas para submissão dos dados em massa (PUT) -->
            <form id="form-atualizar-pontos" action="{{ route('rotas.pontos.update', $rota) }}" method="POST">
                @csrf
                @method('PUT')
            </form>

            <div class="overflow-x-auto rounded-xl border border-slate-200">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th scope="col" class="w-10 px-3 py-3.5 text-center"></th>
                            <th scope="col" class="px-6 py-3.5 text-center w-24">Ordem</th>
                            <th scope="col" class="px-6 py-3.5">Descrição</th>
                            <th scope="col" class="px-6 py-3.5 text-center">Ativo na Rota</th>
                            <th scope="col" class="px-6 py-3.5 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="sortable-pontos" class="divide-y divide-slate-100 text-xs">
                        @foreach ($rota->pontosDeParada->sortBy('pivot.ordem') as $ponto)
                        <tr 
                            data-ponto-id="{{ $ponto->id }}"
                            data-descricao="{{ $ponto->descricao }}"
                            data-lat="{{ str_replace(',', '.', $ponto->latitude) }}"
                            data-lng="{{ str_replace(',', '.', $ponto->longitude) }}"
                            class="hover:bg-slate-50/70 transition-colors"
                        >
                            <td class="px-3 py-4 text-center drag-handle text-slate-400 hover:text-slate-600 cursor-grab active:cursor-grabbing">
                                <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                </svg>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <input
                                    type="number"
                                    form="form-atualizar-pontos"
                                    name="pontos[{{ $ponto->id }}][ordem]"
                                    value="{{ $ponto->pivot->ordem }}"
                                    readonly
                                    tabindex="-1"
                                    class="input-ordem w-12 text-center bg-slate-100 border-slate-200 text-slate-700 font-semibold rounded-lg shadow-inner text-xs select-none cursor-not-allowed focus:ring-0 focus:border-slate-200 py-1">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-800 select-none">
                                {{ $ponto->descricao }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <input
                                    type="checkbox"
                                    form="form-atualizar-pontos"
                                    name="pontos[{{ $ponto->id }}][ativo]"
                                    value="1"
                                    class="input-ativo rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500 cursor-pointer w-4 h-4"
                                    {{ $ponto->pivot->ativo ? 'checked' : '' }}>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <form action="{{ route('rotas.pontos.destroy', [$rota, $ponto]) }}" method="POST" class="inline-block" onsubmit="return confirm('Deseja desvincular este ponto da rota?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold rounded-lg transition cursor-pointer">
                                        Desvincular
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" form="form-atualizar-pontos"
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl transition shadow-md shadow-indigo-500/20 flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Salvar Ordem e Status dos Pontos</span>
                </button>
            </div>
        @else
            <div class="py-8 text-center text-slate-400 font-medium text-xs border border-dashed border-slate-200 rounded-xl">
                Nenhum ponto de parada vinculado a esta rota.
            </div>
        @endif
    </div>

</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<!-- SortableJS CDN -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mapElement = document.getElementById('map');
        const distanciaInfo = document.getElementById('distancia-info');
        const tbody = document.getElementById('sortable-pontos');
        if (!mapElement) return;

        // Redefinição de URLs para evitar marcadores quebrados
        delete L.Icon.Default.prototype._getIconUrl;
        L.Icon.Default.mergeOptions({
            iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
            iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
            shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
        });

        const defaultLat = -23.550520;
        const defaultLng = -46.633309;

        // Inicializa Mapa
        const map = L.map('map').setView([defaultLat, defaultLng], 13);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        let currentMarkers = [];
        let currentPolyline = null;

        // Extrai pontos ativos na tabela em ordem visual
        function getPontosDoDOM() {
            if (!tbody) return [];
            const rows = Array.from(tbody.querySelectorAll('tr'));
            const pontos = [];

            rows.forEach((row, index) => {
                const ativoInput = row.querySelector('.input-ativo');
                if (ativoInput && ativoInput.checked) {
                    pontos.push({
                        id: row.dataset.pontoId,
                        descricao: row.dataset.descricao,
                        lat: parseFloat(row.dataset.lat),
                        lng: parseFloat(row.dataset.lng),
                        ordem: index + 1
                    });
                }
            });

            return pontos;
        }

        // Redesenha Marcadores e Polinha (OSRM)
        function renderizarRota() {
            const pontos = getPontosDoDOM();

            currentMarkers.forEach(m => map.removeLayer(m));
            currentMarkers = [];
            if (currentPolyline) {
                map.removeLayer(currentPolyline);
                currentPolyline = null;
            }

            if (distanciaInfo) {
                distanciaInfo.classList.add('hidden');
            }

            if (pontos.length === 0) return;

            pontos.forEach((ponto, index) => {
                const customIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div style="background-color: #4f46e5; color: white; border-radius: 50%; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 12px; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);">${index + 1}</div>`,
                    iconSize: [28, 28],
                    iconAnchor: [14, 14]
                });

                const marker = L.marker([ponto.lat, ponto.lng], { icon: customIcon })
                    .addTo(map)
                    .bindPopup(`<b>Parada ${index + 1}: ${ponto.descricao}</b>`);

                currentMarkers.push(marker);
            });

            if (pontos.length >= 2) {
                const coordinatesStr = pontos.map(p => `${p.lng},${p.lat}`).join(';');
                const url = `https://router.project-osrm.org/route/v1/driving/${coordinatesStr}?overview=full&geometries=geojson`;

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data.routes && data.routes.length > 0) {
                            const route = data.routes[0];
                            const routeCoordinates = route.geometry.coordinates.map(coord => [coord[1], coord[0]]);

                            currentPolyline = L.polyline(routeCoordinates, {
                                color: '#4f46e5',
                                weight: 5,
                                opacity: 0.8
                            }).addTo(map);

                            map.fitBounds(currentPolyline.getBounds(), { padding: [30, 30] });

                            if (distanciaInfo) {
                                const distanciaKm = (route.distance / 1000).toFixed(1);
                                distanciaInfo.textContent = `Distância Total: ${distanciaKm} km`;
                                distanciaInfo.classList.remove('hidden');
                            }
                        }
                    })
                    .catch(err => console.error("Erro ao carregar rota OSRM:", err));
            } else {
                map.setView([pontos[0].lat, pontos[0].lng], 15);
            }
        }

        // Atualiza a numeração sequencial dos inputs readonly
        function atualizarInputsOrdem() {
            if (!tbody) return;
            const rows = tbody.querySelectorAll('tr');
            rows.forEach((row, index) => {
                const inputOrdem = row.querySelector('.input-ordem');
                if (inputOrdem) {
                    inputOrdem.value = index + 1;
                }
            });
        }

        renderizarRota();

        // Configuração do SortableJS
        if (tbody) {
            Sortable.create(tbody, {
                handle: '.drag-handle',
                animation: 150,
                ghostClass: 'bg-indigo-50',
                onEnd: function () {
                    atualizarInputsOrdem();
                    renderizarRota();
                }
            });

            tbody.addEventListener('change', function (e) {
                if (e.target.classList.contains('input-ativo')) {
                    renderizarRota();
                }
            });
        }

        setTimeout(() => map.invalidateSize(), 300);
    });
</script>
@endsection