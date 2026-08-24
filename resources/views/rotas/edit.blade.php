<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar Rota') }}
            </h2>
            <a href="{{ route('rotas.index') }}" class="text-gray-600 hover:text-gray-900 text-sm font-semibold">Voltar</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
            <div class="flex items-center justify-between rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 shadow-sm" role="alert">
                <div class="flex items-center space-x-3">
                    <svg class="h-5 w-5 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            </div>
            @endif

            <!-- Form: Dados Básicos da Rota -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Dados da Rota</h3>
                <form action="{{ route('rotas.update', $rota) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <x-input-label for="descricao" :value="__('Descrição')" />
                        <x-text-input id="descricao" class="block mt-1 w-full" type="text" name="descricao" :value="old('descricao', $rota->descricao)" required />
                        <x-input-error :messages="$errors->get('descricao')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <label for="ativo" class="inline-flex items-center">
                            <input id="ativo" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="ativo" value="1" {{ old('ativo', $rota->ativo) ? 'checked' : '' }}>
                            <span class="ms-2 text-sm text-gray-600">{{ __('Rota Ativa') }}</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end">
                        <x-primary-button>
                            {{ __('Atualizar Dados Básicos') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <!-- Form: Adicionar Ponto de Parada -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Vincular Novo Ponto de Parada</h3>
                <form action="{{ route('rotas.pontos.store', $rota) }}" method="POST" class="flex items-end gap-4">
                    @csrf
                    <div class="flex-grow">
                        <x-input-label for="ponto_de_parada_id" :value="__('Selecione um Ponto')" />
                        <select id="ponto_de_parada_id" name="ponto_de_parada_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            <option value="">-- Escolha um ponto --</option>
                            @foreach($pontosDisponiveis as $ponto)
                            <option value="{{ $ponto->id }}">
                                {{ $ponto->descricao }} (Lat: {{ $ponto->latitude }}, Long: {{ $ponto->longitude }})
                            </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('ponto_de_parada_id')" class="mt-2" />
                    </div>
                    <x-primary-button>
                        {{ __('Vincular Ponto') }}
                    </x-primary-button>
                </form>
            </div>

            <!-- Prepara a coleção de pontos -->
            @php
                $pontosMapeados = $rota->pontosDeParada
                    ->filter(function($ponto) {
                        return (bool)$ponto->pivot->ativo;
                    })
                    ->map(function($ponto) {
                        return [
                            'id' => $ponto->id,
                            'descricao' => $ponto->descricao,
                            'lat' => (float) $ponto->latitude,
                            'lng' => (float) $ponto->longitude,
                            'ordem' => (int) $ponto->pivot->ordem
                        ];
                    })
                    ->values();
            @endphp

            <!-- Visualização do Mapa com Roteamento por Rodovias -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Trajeto nas Rodovias / Ruas</h3>
                    <div id="distancia-info" class="text-sm font-semibold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-md hidden"></div>
                </div>

                <div 
                    id="map" 
                    style="height: 420px; width: 100%; min-height: 420px; z-index: 1;"
                    class="border border-gray-300 rounded-lg shadow-sm"
                    data-pontos='@json($pontosMapeados)'
                ></div>
            </div>

            <!-- Form: Reordenar / Alterar Status / Desvincular Pontos -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Gerenciar Pontos da Rota</h3>
                    <span class="text-xs text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full border border-gray-200 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11"></path></svg>
                        Arraste as linhas para reordenar
                    </span>
                </div>

                @if($rota->pontosDeParada->count() > 0)
                <!-- Formulário invisível apenas para a atualização em massa (PUT) -->
                <form id="form-atualizar-pontos" action="{{ route('rotas.pontos.update', $rota) }}" method="POST">
                    @csrf
                    @method('PUT')
                </form>

                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="w-10 px-3 py-3"></th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">Ordem</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Descrição</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Ativo na Rota</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="sortable-pontos" class="bg-white divide-y divide-gray-200">
                        @foreach ($rota->pontosDeParada->sortBy('pivot.ordem') as $ponto)
                        <tr 
                            data-ponto-id="{{ $ponto->id }}"
                            data-descricao="{{ $ponto->descricao }}"
                            data-lat="{{ $ponto->latitude }}"
                            data-lng="{{ $ponto->longitude }}"
                            class="hover:bg-gray-50 transition-colors cursor-grab active:cursor-grabbing"
                        >
                            <td class="px-3 py-4 text-center drag-handle text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                </svg>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <!-- Campo Readonly: envia o valor no submit mas impede edição manual -->
                                <input
                                    type="number"
                                    form="form-atualizar-pontos"
                                    name="pontos[{{ $ponto->id }}][ordem]"
                                    value="{{ $ponto->pivot->ordem }}"
                                    readonly
                                    tabindex="-1"
                                    class="input-ordem w-12 text-center bg-gray-100 border-gray-200 text-gray-600 font-semibold rounded-md shadow-inner text-sm select-none cursor-not-allowed focus:ring-0 focus:border-gray-200">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 select-none">
                                {{ $ponto->descricao }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <input
                                    type="checkbox"
                                    form="form-atualizar-pontos"
                                    name="pontos[{{ $ponto->id }}][ativo]"
                                    value="1"
                                    class="input-ativo rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 cursor-pointer"
                                    {{ $ponto->pivot->ativo ? 'checked' : '' }}>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <form action="{{ route('rotas.pontos.destroy', [$rota, $ponto]) }}" method="POST" class="inline-block" onsubmit="return confirm('Deseja desvincular este ponto da rota?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-md transition-colors text-xs font-semibold shadow-sm">
                                        Desvincular
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="flex justify-end mt-4">
                    <x-primary-button form="form-atualizar-pontos">
                        {{ __('Salvar Ordem e Status dos Pontos') }}
                    </x-primary-button>
                </div>
                @else
                <p class="text-sm text-gray-500 py-2">Nenhum ponto de parada vinculado a esta rota.</p>
                @endif
            </div>

        </div>
    </div>

    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- SortableJS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const mapElement = document.getElementById('map');
            const distanciaInfo = document.getElementById('distancia-info');
            const tbody = document.getElementById('sortable-pontos');
            if (!mapElement) return;

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

            setTimeout(() => map.invalidateSize(), 200);
        });
    </script>
</x-app-layout>