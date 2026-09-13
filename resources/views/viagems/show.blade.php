@extends('layouts.admin')

@section('title', 'Detalhes da Viagem')
@section('header_title', 'Detalhes da Viagem')

@section('content')
<!-- Leaflet CSS carregado diretamente -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    .leaflet-container img {
        max-width: none !important;
        max-height: none !important;
    }

    @media print {
        body {
            background-color: #ffffff !important;
            color: #000000 !important;
        }
        .print\:hidden, nav, header, sidebar {
            display: none !important;
        }
        .print\:block {
            display: block !important;
        }
        .print\:shadow-none {
            box-shadow: none !important;
        }
        .print\:border {
            border: 1px solid #e2e8f0 !important;
        }
        .py-12, .space-y-6 > * + * {
            margin-top: 0 !important;
            padding-top: 0 !important;
        }
    }
</style>

@php
    $pontosAtivos = optional($viagem->rota)->pontosDeParada
        ? $viagem->rota->pontosDeParada
            ->filter(fn($p) => (bool) $p->pivot->ativo)
            ->sortBy('pivot.ordem')
            ->map(fn($p) => [
                'id' => $p->id,
                'descricao' => $p->descricao,
                'lat' => (float) str_replace(',', '.', $p->latitude),
                'lng' => (float) str_replace(',', '.', $p->longitude),
                'ordem' => (int) $p->pivot->ordem
            ])
            ->values()
        : collect([]);
@endphp

<div class="max-w-7xl mx-auto space-y-6">

    <!-- Cabeçalho visível APENAS na Impressão -->
    <div class="hidden print:block text-center border-b border-slate-200 pb-4 mb-4">
        <h1 class="text-2xl font-bold text-slate-900">FICHA DE EMBARQUE - VIAGEM</h1>
        <p class="text-xs text-slate-500 mt-1">Aponte a câmera do celular para o QR Code para confirmar a presença</p>
    </div>

    <!-- Card Topo / Cabeçalho (Oculto na impressão) -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4 print:hidden">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('viagems.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
                    &larr; Voltar para Viagens
                </a>
                <h2 class="text-base font-bold text-slate-800">
                    Viagem: {{ $viagem->rota->descricao ?? 'Rota não definida' }}
                </h2>
            </div>
            <p class="text-xs text-slate-500 mt-1">Visão geral do manifesto, horário, rota e lista de passageiros.</p>
        </div>

        <div class="flex items-center gap-3 self-start sm:self-auto">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $viagem->ativo ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $viagem->ativo ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                {{ $viagem->ativo ? 'Viagem Ativa' : 'Viagem Inativa' }}
            </span>

            <button type="button" onclick="window.print()"
                class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs rounded-xl transition shadow-md shadow-slate-800/20 flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Imprimir Ficha</span>
            </button>

            <a href="{{ route('viagems.edit', $viagem) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl transition shadow-md shadow-indigo-500/20 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span>Editar Viagem</span>
            </a>
        </div>
    </div>

    <!-- Cards Informativos + QR Code -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2 print:shadow-none print:border">
            <span class="text-[11px] font-bold uppercase text-slate-400 tracking-wider">Informações Operacionais</span>
            <p class="text-xs text-slate-700"><strong>Rota:</strong> {{ $viagem->rota->descricao ?? 'N/A' }}</p>
            <p class="text-xs text-slate-700"><strong>Motorista:</strong> {{ $viagem->motorista->usuario->name ?? 'N/A' }}</p>
            <p class="text-xs text-slate-700"><strong>Veículo:</strong> {{ $viagem->veiculo->descricao ?? $viagem->veiculo->modelo ?? 'N/A' }} <span class="font-mono font-bold uppercase">({{ $viagem->veiculo->placa ?? '' }})</span></p>
            <p class="text-xs text-slate-700"><strong>Passageiros:</strong> {{ $viagem->passageiros->count() }}</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-2 print:shadow-none print:border">
            <span class="text-[11px] font-bold uppercase text-slate-400 tracking-wider">Horários Previstos</span>
            <p class="text-xs text-slate-700"><strong>Saída:</strong> {{ $viagem->data_hora_saida ? $viagem->data_hora_saida->format('d/m/Y H:i') : '-' }}</p>
            <p class="text-xs text-slate-700"><strong>Chegada:</strong> {{ $viagem->data_hora_chegada ? $viagem->data_hora_chegada->format('d/m/Y H:i') : '-' }}</p>
        </div>

        <!-- Card QR Code -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col items-center justify-center text-center print:shadow-none print:border">
            <span class="text-[11px] font-bold uppercase text-slate-400 tracking-wider mb-2">Presença por QR Code</span>
            
            @php
                $urlCheckin = url('/viagens/checkin/' . $viagem->codigo_qr);
                $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($urlCheckin);
            @endphp

            <img src="{{ $qrCodeUrl }}" alt="QR Code da Viagem" class="w-28 h-28 my-1 border border-slate-200 p-1.5 rounded-xl bg-white shadow-sm" />
            <p class="text-[10px] font-mono font-semibold text-slate-500 mt-1 break-all">{{ $viagem->codigo_qr }}</p>
        </div>
    </div>

    <!-- Mapa da Rota (Oculto na Impressão) -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4 print:hidden">
        <div class="flex justify-between items-center px-1">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Mapa do Percurso</h3>
            <div id="distancia-info" class="text-xs font-bold text-indigo-700 bg-indigo-50 px-3 py-1 rounded-lg border border-indigo-100 hidden"></div>
        </div>

        <div 
            id="map" 
            style="height: 400px; width: 100%; min-height: 400px;"
            class="rounded-xl border border-slate-200 z-10 overflow-hidden shadow-sm"
            data-pontos='@json($pontosAtivos)'
        ></div>
    </div>

    <!-- Lista de Passageiros -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4 print:shadow-none print:border">
        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Passageiros Confirmados / Embarcados</h3>

        @if($viagem->passageiros->count() > 0)
        <div class="overflow-x-auto rounded-xl border border-slate-200">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th scope="col" class="px-6 py-3.5">Passageiro</th>
                        <th scope="col" class="px-6 py-3.5 text-center">Embarque</th>
                        <th scope="col" class="px-6 py-3.5 text-center">Desembarque</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @foreach ($viagem->passageiros as $passageiro)
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="px-6 py-4 font-semibold text-slate-800">
                            {{ $passageiro->name }}
                            <div class="text-[11px] font-normal text-slate-400">{{ $passageiro->email }}</div>
                        </td>
                        <td class="px-6 py-4 text-center font-medium text-slate-600">
                            {{ $passageiro->pivot->data_hora_saida ? \Carbon\Carbon::parse($passageiro->pivot->data_hora_saida)->format('d/m/Y H:i') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-center font-medium text-slate-600">
                            {{ $passageiro->pivot->data_hora_chegada ? \Carbon\Carbon::parse($passageiro->pivot->data_hora_chegada)->format('d/m/Y H:i') : '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="py-8 text-center text-slate-400 font-medium text-xs border border-dashed border-slate-200 rounded-xl">
            Nenhum passageiro embarcado nesta viagem até o momento.
        </div>
        @endif
    </div>

</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mapElement = document.getElementById('map');
        const distanciaInfo = document.getElementById('distancia-info');
        if (!mapElement) return;

        delete L.Icon.Default.prototype._getIconUrl;
        L.Icon.Default.mergeOptions({
            iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
            iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
            shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
        });

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
                })
                .catch(err => console.error("Erro ao carregar rota OSRM:", err));
        } else {
            map.setView([pontos[0].lat, pontos[0].lng], 15);
        }

        setTimeout(() => map.invalidateSize(), 300);
    });
</script>
@endsection