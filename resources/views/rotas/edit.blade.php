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

            <!-- Prepara os pontos mapeados sem causar erro de sintaxe no Blade -->
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
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Gerenciar Pontos da Rota</h3>

                @if($rota->pontosDeParada->count() > 0)
                <!-- Formulário invisível apenas para a atualização em massa (PUT) -->
                <form id="form-atualizar-pontos" action="{{ route('rotas.pontos.update', $rota) }}" method="POST">
                    @csrf
                    @method('PUT')
                </form>

                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">Ordem</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Descrição</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Ativo na Rota</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($rota->pontosDeParada as $ponto)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <!-- O atributo form="form-atualizar-pontos" vincula este input ao formulário do PUT -->
                                <input
                                    type="number"
                                    form="form-atualizar-pontos"
                                    name="pontos[{{ $ponto->id }}][ordem]"
                                    value="{{ $ponto->pivot->ordem }}"
                                    min="1"
                                    class="w-16 text-center border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                                    required>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $ponto->descricao }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <!-- O atributo form="form-atualizar-pontos" vincula este checkbox ao formulário do PUT -->
                                <input
                                    type="checkbox"
                                    form="form-atualizar-pontos"
                                    name="pontos[{{ $ponto->id }}][ativo]"
                                    value="1"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                    {{ $ponto->pivot->ativo ? 'checked' : '' }}>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <!-- Formulário DELETE completamente independente -->
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
                    <!-- Botão de submit apontando para o formulário PUT -->
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const mapElement = document.getElementById('map');
            const distanciaInfo = document.getElementById('distancia-info');
            if (!mapElement) return;

            // Carrega os pontos ativos e ordena pela coluna pivot 'ordem'
            let pontos = JSON.parse(mapElement.dataset.pontos || '[]');
            pontos.sort((a, b) => a.ordem - b.ordem);

            const defaultLat = -23.550520;
            const defaultLng = -46.633309;

            const map = L.map('map').setView(
                pontos.length > 0 ? [pontos[0].lat, pontos[0].lng] : [defaultLat, defaultLng], 
                13
            );

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            if (pontos.length > 0) {
                // Adiciona Marcadores Numerados
                pontos.forEach((ponto, index) => {
                    const customIcon = L.divIcon({
                        className: 'custom-div-icon',
                        html: `<div style="background-color: #4f46e5; color: white; border-radius: 50%; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 12px; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);">${index + 1}</div>`,
                        iconSize: [28, 28],
                        iconAnchor: [14, 14]
                    });

                    L.marker([ponto.lat, ponto.lng], { icon: customIcon })
                        .addTo(map)
                        .bindPopup(`<b>Parada ${index + 1}: ${ponto.descricao}</b>`);
                });

                // Se houver 2 ou mais pontos, busca o trajeto pelas RODOVIAS via OSRM API
                if (pontos.length >= 2) {
                    const coordinatesStr = pontos.map(p => `${p.lng},${p.lat}`).join(';');
                    const url = `https://router.project-osrm.org/route/v1/driving/${coordinatesStr}?overview=full&geometries=geojson`;

                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            if (data.routes && data.routes.length > 0) {
                                const route = data.routes[0];

                                // Converte o formato GeoJSON [lng, lat] para o Leaflet [lat, lng]
                                const routeCoordinates = route.geometry.coordinates.map(coord => [coord[1], coord[0]]);

                                // Desenha a linha seguindo as rodovias
                                const polyline = L.polyline(routeCoordinates, {
                                    color: '#4f46e5',
                                    weight: 5,
                                    opacity: 0.8
                                }).addTo(map);

                                // Ajusta o zoom do mapa para o trajeto
                                map.fitBounds(polyline.getBounds(), { padding: [30, 30] });

                                // Exibe a distância total calculada pelas rodovias
                                if (distanciaInfo) {
                                    const distanciaKm = (route.distance / 1000).toFixed(1);
                                    distanciaInfo.textContent = `Distância Total: ${distanciaKm} km`;
                                    distanciaInfo.classList.remove('hidden');
                                }
                            }
                        })
                        .catch(err => {
                            console.error("Erro ao carregar rota OSRM:", err);
                        });
                } else {
                    map.setView([pontos[0].lat, pontos[0].lng], 15);
                }
            }

            setTimeout(function () {
                map.invalidateSize();
            }, 200);
        });
    </script>
</x-app-layout>