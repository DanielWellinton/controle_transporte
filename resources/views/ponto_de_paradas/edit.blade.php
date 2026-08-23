<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar Ponto de Parada') }}
            </h2>
            <a href="{{ route('ponto_de_paradas.index') }}" class="text-gray-600 hover:text-gray-900 text-sm font-semibold">Voltar</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('ponto_de_paradas.update', $pontoDeParada) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Formulário -->
                        <div>
                            <!-- Descrição -->
                            <div class="mb-4">
                                <x-input-label for="descricao" :value="__('Descrição')" />
                                <x-text-input id="descricao" class="block mt-1 w-full" type="text" name="descricao" :value="old('descricao', $pontoDeParada->descricao)" required />
                                <x-input-error :messages="$errors->get('descricao')" class="mt-2" />
                            </div>

                            <!-- Latitude -->
                            <div class="mb-4">
                                <x-input-label for="latitude" :value="__('Latitude')" />
                                <x-text-input id="latitude" class="block mt-1 w-full font-mono" type="text" name="latitude" :value="old('latitude', $pontoDeParada->latitude)" required readonly />
                                <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                            </div>

                            <!-- Longitude -->
                            <div class="mb-4">
                                <x-input-label for="longitude" :value="__('Longitude')" />
                                <x-text-input id="longitude" class="block mt-1 w-full font-mono" type="text" name="longitude" :value="old('longitude', $pontoDeParada->longitude)" required readonly />
                                <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                            </div>

                            <!-- Ativo -->
                            <div class="mb-4">
                                <label for="ativo" class="inline-flex items-center">
                                    <input id="ativo" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="ativo" value="1" {{ old('ativo', $pontoDeParada->ativo) ? 'checked' : '' }}>
                                    <span class="ms-2 text-sm text-gray-600">{{ __('Ponto de Parada Ativo') }}</span>
                                </label>
                            </div>
                        </div>

                        <!-- Seletor de Mapa -->
                        <div>
                            <x-input-label :value="__('Localização no Mapa')" class="mb-2" />
                            <div
                                id="map"
                                style="height: 350px; width: 100%; min-height: 350px; z-index: 1;"
                                class="border border-gray-300 rounded-lg shadow-sm"
                                data-lat="{{ old('latitude', $pontoDeParada->latitude ?? -23.550520) }}"
                                data-lng="{{ old('longitude', $pontoDeParada->longitude ?? -46.633309) }}"></div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6 gap-3">
                        <a href="{{ route('ponto_de_paradas.index') }}" class="text-gray-600 hover:text-gray-900 text-sm font-semibold">Cancelar</a>
                        <x-primary-button>
                            {{ __('Atualizar') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Leaflet CSS e JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mapElement = document.getElementById('map');
            if (!mapElement) return;

            const initialLat = parseFloat(mapElement.dataset.lat) || -23.550520;
            const initialLng = parseFloat(mapElement.dataset.lng) || -46.633309;

            // Inicializa o mapa
            const map = L.map('map').setView([initialLat, initialLng], 15);

            // Tile Layer usando OpenStreetMap (Sem bloqueios de API / Headers)
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            let marker = L.marker([initialLat, initialLng], {
                draggable: true
            }).addTo(map);

            function updateInputs(lat, lng) {
                const latInput = document.getElementById('latitude');
                const lngInput = document.getElementById('longitude');

                if (latInput) latInput.value = lat.toFixed(7);
                if (lngInput) lngInput.value = lng.toFixed(7);
            }

            marker.on('dragend', function(e) {
                const position = marker.getLatLng();
                updateInputs(position.lat, position.lng);
            });

            map.on('click', function(e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;
                marker.setLatLng([lat, lng]);
                updateInputs(lat, lng);
            });

            // Força a recalcular o tamanho da div do mapa após a página carregar completamente
            setTimeout(function() {
                map.invalidateSize();
            }, 200);
        });
    </script>
</x-app-layout>