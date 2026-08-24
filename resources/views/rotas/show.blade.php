<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $rota->descricao }}
                </h2>
                @if($rota->ativo)
                    <span class="bg-emerald-100 text-emerald-800 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-emerald-200">Ativa</span>
                @else
                    <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-gray-200">Inativa</span>
                @endif
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('rotas.edit', $rota) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 transition ease-in-out duration-150 shadow-sm">
                    Editar Rota
                </a>
                <a href="{{ route('rotas.index') }}" class="text-gray-600 hover:text-gray-900 text-sm font-semibold">Voltar</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Mapeamento prévio apenas dos pontos ativos -->
            @php
                $pontosAtivos = $rota->pontosDeParada
                    ->filter(fn($p) => (bool) $p->pivot->ativo)
                    ->sortBy('pivot.ordem')
                    ->map(fn($p) => [
                        'id' => $p->id,
                        'descricao' => $p->descricao,
                        'lat' => (float) $p->latitude,
                        'lng' => (float) $p->longitude,
                        'ordem' => (int) $p->pivot->ordem
                    ])
                    ->values();
            @endphp

            <!-- Mapa Interativo -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Visualização do Trajeto</h3>
                    <div id="distancia-info" class="text-sm font-semibold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-md hidden"></div>
                </div>

                <div 
                    id="map" 
                    style="height: 450px; width: 100%; min-height: 450px; z-index: 1;"
                    class="border border-gray-300 rounded-lg shadow-sm"
                    data-pontos='@json($pontosAtivos)'
                ></div>
            </div>

            <!-- Tabela de Itinerário Completo -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Itinerário de Paradas</h3>

                @if($rota->pontosDeParada->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-20">Parada</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Descrição do Ponto</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Coordenadas</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status na Rota</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($rota->pontosDeParada->sortBy('pivot.ordem') as $ponto)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 text-xs font-bold rounded-full {{ $ponto->pivot->ativo ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-400' }}">
                                    {{ $ponto->pivot->ordem }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $ponto->descricao }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-xs font-mono text-gray-500">
                                {{ $ponto->latitude }}, {{ $ponto->longitude }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($ponto->pivot->ativo)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                        Ativo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                        Ignorado
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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

            const defaultLat = -23.550520;
            const defaultLng = -46.633309;

            const map = L.map('map').setView([defaultLat, defaultLng], 13);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            const pontos = JSON.parse(mapElement.dataset.pontos || '[]');

            if (pontos.length === 0) return;

            // Adiciona marcadores numerados
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

            // Traça rodovia com OSRM
            if (pontos.length >= 2) {
                const coordinatesStr = pontos.map(p => `${p.lng},${p.lat}`).join(';');
                const url = `https://router.project-osrm.org/route/v1/driving/${coordinatesStr}?overview=full&geometries=geojson`;

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data.routes && data.routes.length > 0) {
                            const route = data.routes[0];
                            const routeCoordinates = route.geometry.coordinates.map(coord => [coord[1], coord[0]]);

                            const polyline = L.polyline(routeCoordinates, {
                                color: '#4f46e5',
                                weight: 5,
                                opacity: 0.8
                            }).addTo(map);

                            map.fitBounds(polyline.getBounds(), { padding: [30, 30] });

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

            setTimeout(() => map.invalidateSize(), 200);
        });
    </script>
</x-app-layout>