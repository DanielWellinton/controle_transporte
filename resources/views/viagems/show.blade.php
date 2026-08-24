<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center print:hidden">
            <div class="flex items-center gap-3">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Viagem: {{ $viagem->rota->descricao ?? 'Rota não definida' }}
                </h2>
                @if($viagem->ativo)
                    <span class="bg-emerald-100 text-emerald-800 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-emerald-200">Ativa</span>
                @else
                    <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-gray-200">Inativa</span>
                @endif
            </div>
            <div class="flex items-center gap-3">
                <!-- Botão de Impressão -->
                <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white font-semibold text-xs uppercase tracking-widest rounded-md shadow-sm transition">
    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
        <path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/>
    </svg>
    Imprimir Ficha
</button>
                <a href="{{ route('viagems.index') }}" class="text-gray-600 hover:text-gray-900 text-sm font-semibold">Voltar</a>
            </div>
        </div>
    </x-slot>

    <!-- Estilos específicos para a impressão da ficha de embarque -->
    <style>
        @media print {
            body {
                background-color: #ffffff !important;
                color: #000000 !important;
            }
            .print\:hidden, nav, header {
                display: none !important;
            }
            .print\:block {
                display: block !important;
            }
            .print\:shadow-none {
                box-shadow: none !important;
            }
            .print\:border {
                border: 1px solid #e5e7eb !important;
            }
            .py-12 {
                padding-top: 0 !important;
                padding-bottom: 0 !important;
            }
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Cabeçalho visível APENAS na Impressão -->
            <div class="hidden print:block text-center border-b pb-4 mb-4">
                <h1 class="text-2xl font-bold text-gray-900">FICHA DE EMBARQUE - VIAGEM</h1>
                <p class="text-sm text-gray-600">Aponte a câmera do seu celular para o QR Code para confirmar a presença</p>
            </div>

            <!-- Cards Informativos + QR Code -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-sm space-y-2 border border-gray-100 print:shadow-none print:border">
                    <span class="text-xs font-semibold uppercase text-gray-400">Informações Operacionais</span>
                    <p class="text-sm text-gray-700"><strong>Rota:</strong> {{ $viagem->rota->descricao ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-700"><strong>Motorista:</strong> {{ $viagem->motorista->usuario->name ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-700"><strong>Veículo:</strong> {{ $viagem->veiculo->descricao ?? 'N/A' }} ({{ $viagem->veiculo->placa ?? '' }})</p>
                    <p class="text-sm text-gray-700"><strong>Passageiros:</strong> {{ $viagem->passageiros->count() }}</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm space-y-2 border border-gray-100 print:shadow-none print:border">
                    <span class="text-xs font-semibold uppercase text-gray-400">Horários</span>
                    <p class="text-sm text-gray-700"><strong>Saída:</strong> {{ $viagem->data_hora_saida ? $viagem->data_hora_saida->format('d/m/Y H:i') : '-' }}</p>
                    <p class="text-sm text-gray-700"><strong>Chegada:</strong> {{ $viagem->data_hora_chegada ? $viagem->data_hora_chegada->format('d/m/Y H:i') : '-' }}</p>
                </div>

                <!-- Card com Imagem do QR Code -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center print:shadow-none print:border">
                    <span class="text-xs font-semibold uppercase text-gray-400 mb-2">Presença por QR Code</span>
                    
                    @php
                        $urlCheckin = url('/viagens/checkin/' . $viagem->codigo_qr);
                        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($urlCheckin);
                    @endphp

                    <img src="{{ $qrCodeUrl }}" alt="QR Code da Viagem" class="w-32 h-32 my-1 border p-1 rounded bg-white shadow-sm" />
                    <p class="text-[10px] font-mono text-gray-500 mt-1 break-all">{{ $viagem->codigo_qr }}</p>
                </div>
            </div>

            <!-- Pontos da Rota para o JavaScript -->
            @php
                $pontosAtivos = optional($viagem->rota)->pontosDeParada
                    ? $viagem->rota->pontosDeParada
                        ->filter(fn($p) => (bool) $p->pivot->ativo)
                        ->sortBy('pivot.ordem')
                        ->map(fn($p) => [
                            'id' => $p->id,
                            'descricao' => $p->descricao,
                            'lat' => (float) $p->latitude,
                            'lng' => (float) $p->longitude,
                            'ordem' => (int) $p->pivot->ordem
                        ])
                        ->values()
                    : collect([]);
            @endphp

            <!-- Mapa da Rota (Oculto na Impressão) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 print:hidden">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Mapa do Percurso</h3>
                    <div id="distancia-info" class="text-sm font-semibold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-md hidden"></div>
                </div>

                <div 
                    id="map" 
                    style="height: 400px; width: 100%; z-index: 1;"
                    class="border border-gray-300 rounded-lg shadow-sm"
                    data-pontos='@json($pontosAtivos)'
                ></div>
            </div>

            <!-- Lista de Passageiros -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 print:shadow-none print:border">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Passageiros Confirmados / Embarcados</h3>

                @if($viagem->passageiros->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Passageiro</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Embarque</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Desembarque</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($viagem->passageiros as $passageiro)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $passageiro->name }}
                                <div class="text-xs text-gray-400">{{ $passageiro->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-600">
                                {{ $passageiro->pivot->data_hora_saida ? \Carbon\Carbon::parse($passageiro->pivot->data_hora_saida)->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-600">
                                {{ $passageiro->pivot->data_hora_chegada ? \Carbon\Carbon::parse($passageiro->pivot->data_hora_chegada)->format('d/m/Y H:i') : '-' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-sm text-gray-500 py-2">Nenhum passageiro embarcado nesta viagem até o momento.</p>
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

            const map = L.map('map').setView([-23.550520, -46.633309], 13);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            const pontos = JSON.parse(mapElement.dataset.pontos || '[]');
            if (pontos.length === 0) return;

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
                    });
            } else {
                map.setView([pontos[0].lat, pontos[0].lng], 15);
            }

            setTimeout(() => map.invalidateSize(), 200);
        });
    </script>
</x-app-layout>