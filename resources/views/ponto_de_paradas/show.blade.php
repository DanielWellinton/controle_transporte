<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalhes do Ponto de Parada') }}
            </h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('ponto_de_paradas.index') }}" class="text-gray-600 hover:text-gray-900 text-sm font-semibold">Voltar</a>
                <a href="{{ route('ponto_de_paradas.edit', $pontoDeParada) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-sm transition-colors shadow-sm">
                    Editar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <!-- Informações Principais -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 pb-6 border-b border-gray-100">
                    <div>
                        <p class="text-sm font-semibold text-gray-500">Descrição:</p>
                        <p class="text-lg font-bold text-gray-900">{{ $pontoDeParada->descricao }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-500">Coordenadas (Lat / Lng):</p>
                        <p class="text-base font-mono text-gray-800">{{ $pontoDeParada->latitude }}, {{ $pontoDeParada->longitude }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-500">Status:</p>
                        <span class="mt-1 px-3 py-1 inline-flex text-xs font-semibold rounded-full {{ $pontoDeParada->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $pontoDeParada->ativo ? 'Ativo' : 'Inativo' }}
                        </span>
                    </div>
                </div>

                <!-- Visualização Interativa do Mapa -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Localização Cadastrada:</h3>
                    <div 
                        id="map" 
                        style="height: 400px; width: 100%; min-height: 400px; z-index: 1;"
                        class="border border-gray-300 rounded-lg shadow-sm"
                        data-lat="{{ $pontoDeParada->latitude }}"
                        data-lng="{{ $pontoDeParada->longitude }}"
                        data-title="{{ $pontoDeParada->descricao }}"
                    ></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet CSS e JS (Gratuito & OpenSource) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const mapElement = document.getElementById('map');
            if (!mapElement) return;

            const lat = parseFloat(mapElement.dataset.lat);
            const lng = parseFloat(mapElement.dataset.lng);
            const title = mapElement.dataset.title;

            // Inicializa o mapa centralizado no ponto
            const map = L.map('map').setView([lat, lng], 16);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            // Marcador fixo com popup descritivo
            L.marker([lat, lng])
                .addTo(map)
                .bindPopup(`<b>${title}</b><br>Lat: ${lat}<br>Lng: ${lng}`)
                .openPopup();

            setTimeout(function () {
                map.invalidateSize();
            }, 200);
        });
    </script>
</x-app-layout>