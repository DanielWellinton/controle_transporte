<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ url('/passageiros') }}" class="text-gray-500 hover:text-gray-700 font-bold">&larr; Voltar</a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Viagem: {{ $viagem->rota->descricao ?? 'Detalhes' }}
            </h2>
        </div>
    </x-slot>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    @php
        $pontosAtivos = optional($viagem->rota)->pontosDeParada
            ? $viagem->rota->pontosDeParada
                ->filter(fn($p) => isset($p->pivot->ativo) ? (bool)$p->pivot->ativo : true)
                ->sortBy('pivot.ordem')
                ->map(fn($p, $key) => [
                    'id' => $p->id,
                    'descricao' => $p->descricao,
                    'lat' => (float) str_replace(',', '.', $p->latitude),
                    'lng' => (float) str_replace(',', '.', $p->longitude),
                    'ordem' => isset($p->pivot->ordem) ? (int)$p->pivot->ordem : $key
                ])
                ->values()
            : collect([]);
    @endphp

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Exibição de Mensagens de Erro da Validação do Backend --}}
            @if ($errors->any())
                <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm" role="alert">
                    <p class="font-bold">Atenção!</p>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <!-- ÁREA DO MAPA -->
                <div class="lg:col-span-7 bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-sm font-bold text-gray-700">Mapa do Trajeto e Embarque</h3>
                        <div id="distancia-info" class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md hidden"></div>
                    </div>
                    
                    <div 
                        id="map" 
                        style="height: 520px; width: 100%;" 
                        class="rounded-lg border border-gray-200 z-0"
                        data-pontos='@json($pontosAtivos)'
                    ></div>
                </div>

                <!-- FORMULÁRIO DE SELEÇÃO DOS PONTOS -->
                <div class="lg:col-span-5 bg-white rounded-xl shadow-sm p-6 flex flex-col justify-between border border-gray-100">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 mb-1">Selecione seus Pontos</h3>
                        <p class="text-xs text-gray-500 mb-4">Escolha os locais clicando no mapa ou nos seletores abaixo.</p>

                        <!-- PAINEL DE RESUMO INTERATIVO -->
                        <div class="mb-5 space-y-2">
                            <div id="card-embarque" class="p-3 bg-gray-50 border border-gray-200 rounded-lg transition-all">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 block">🟢 Ponto de Embarque</span>
                                <p id="texto-embarque" class="text-xs font-bold text-gray-700 mt-0.5">Nenhum selecionado</p>
                            </div>

                            <div id="card-desembarque" class="p-3 bg-gray-50 border border-gray-200 rounded-lg transition-all">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-rose-600 block">🔴 Ponto de Desembarque</span>
                                <p id="texto-desembarque" class="text-xs font-bold text-gray-700 mt-0.5">Nenhum selecionado</p>
                            </div>
                        </div>

                        <!-- FORMULÁRIO -->
                        <form id="form-pontos" action="{{ route('passageiros.selecionar-pontos', $viagem->id) }}" method="POST" class="space-y-4">
                            @csrf

                            <!-- Ponto de Embarque -->
                            <div>
                                <label for="ponto_de_parada_saida_id" class="block text-xs font-bold uppercase text-gray-600 mb-1">
                                    Embarque (Saída)
                                </label>
                                <select name="ponto_de_parada_saida_id" id="ponto_de_parada_saida_id" required
                                        onchange="aoMudarSelect()"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    <option value="">Selecione o ponto de embarque...</option>
                                    @foreach($pontosAtivos as $ponto)
                                        <option value="{{ $ponto['id'] }}" data-ordem="{{ $ponto['ordem'] }}"
                                            {{ (old('ponto_de_parada_saida_id', $reserva->ponto_de_parada_saida_id ?? '') == $ponto['id']) ? 'selected' : '' }}>
                                            {{ $ponto['ordem'] + 1 }}. {{ $ponto['descricao'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Ponto de Desembarque -->
                            <div>
                                <label for="ponto_de_parada_chegada_id" class="block text-xs font-bold uppercase text-gray-600 mb-1">
                                    Desembarque (Chegada)
                                </label>
                                <select name="ponto_de_parada_chegada_id" id="ponto_de_parada_chegada_id" required
                                        onchange="aoMudarSelect()"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    <option value="">Selecione o ponto de desembarque...</option>
                                    @foreach($pontosAtivos as $ponto)
                                        <option value="{{ $ponto['id'] }}" data-ordem="{{ $ponto['ordem'] }}"
                                            {{ (old('ponto_de_parada_chegada_id', $reserva->ponto_de_parada_chegada_id ?? '') == $ponto['id']) ? 'selected' : '' }}>
                                            {{ $ponto['ordem'] + 1 }}. {{ $ponto['descricao'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" 
                                    class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-lg uppercase tracking-wider shadow transition">
                                Confirmar Reserva
                            </button>
                        </form>
                    </div>

                    <!-- Dados Adicionais -->
                    <div class="mt-6 pt-4 border-t border-gray-100 text-xs text-gray-500 space-y-1">
                        <p><strong>Veículo:</strong> {{ $viagem->veiculo->modelo ?? 'N/A' }} ({{ $viagem->veiculo->placa ?? 'Sem placa' }})</p>
                        <p><strong>Motorista:</strong> {{ $viagem->motorista->usuario->name ?? 'Não definido' }}</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <script>
        let map;
        let marcadores = {};
        let linhaRotaGeral = null;
        let linhaTrechoPassageiro = null;
        let pontosData = [];

        document.addEventListener("DOMContentLoaded", function() {
            const mapElement = document.getElementById('map');
            if (!mapElement) return;

            pontosData = JSON.parse(mapElement.dataset.pontos || '[]');

            map = L.map('map').setView([-15.7801, -47.9292], 4);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            setTimeout(() => { map.invalidateSize(); }, 300);

            if (pontosData.length === 0) return;

            // 1. Cria os marcadores no mapa
            pontosData.forEach((ponto, index) => {
                const iconNumerado = criarIconeNumerado(index + 1, '#4f46e5');

                const marker = L.marker([ponto.lat, ponto.lng], { icon: iconNumerado }).addTo(map);

                const popupContent = `
                    <div class="p-1 text-center space-y-2">
                        <strong class="block text-sm text-gray-800">Parada ${index + 1}: ${ponto.descricao}</strong>
                        <div class="flex flex-col space-y-1.5">
                            <button type="button" onclick="definirPonto(${ponto.id}, 'saida')" 
                                    class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded shadow-sm transition">
                                🟢 Embarcar Aqui
                            </button>
                            <button type="button" onclick="definirPonto(${ponto.id}, 'chegada')" 
                                    class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded shadow-sm transition">
                                🔴 Desembarcar Aqui
                            </button>
                        </div>
                    </div>
                `;

                marker.bindPopup(popupContent);
                marcadores[ponto.id] = { marker: marker, lat: ponto.lat, lng: ponto.lng, descricao: ponto.descricao, index: index, ordem: ponto.ordem };
            });

            // 2. Traça a rota completa original via OSRM pelas ruas
            if (pontosData.length >= 2) {
                const coordinatesStr = pontosData.map(p => `${p.lng},${p.lat}`).join(';');
                const url = `https://router.project-osrm.org/route/v1/driving/${coordinatesStr}?overview=full&geometries=geojson`;

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data.routes && data.routes.length > 0) {
                            const routeCoordinates = data.routes[0].geometry.coordinates.map(c => [c[1], c[0]]);

                            linhaRotaGeral = L.polyline(routeCoordinates, {
                                color: '#9CA3AF',
                                weight: 4,
                                opacity: 0.6,
                                dashArray: '6, 6'
                            }).addTo(map);

                            map.fitBounds(linhaRotaGeral.getBounds(), { padding: [40, 40] });
                        }
                    })
                    .catch(err => console.error("Erro ao carregar rota OSRM:", err));
            } else {
                map.setView([pontosData[0].lat, pontosData[0].lng], 14);
            }

            // Inicializa validação das opções e rota caso venha pré-preenchido
            aoMudarSelect();
        });

        function criarIconeNumerado(numero, corFundo) {
            return L.divIcon({
                className: 'custom-div-icon',
                html: `<div style="background-color: ${corFundo}; color: white; border-radius: 50%; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 12px; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);">${numero}</div>`,
                iconSize: [28, 28],
                iconAnchor: [14, 14]
            });
        }

        // Função ao interagir via os selects
        function aoMudarSelect() {
            validarEFiltrarOpcoes();
            atualizarTrajetoEInterface();
        }

        // Bloqueia e desabilita nos selects os pontos que infringem o sentido único da viagem
        function validarEFiltrarOpcoes() {
            const selectSaida = document.getElementById('ponto_de_parada_saida_id');
            const selectChegada = document.getElementById('ponto_de_parada_chegada_id');

            const selectedSaidaOpt = selectSaida.options[selectSaida.selectedIndex];
            const selectedChegadaOpt = selectChegada.options[selectChegada.selectedIndex];

            const ordemSaida = selectedSaidaOpt && selectedSaidaOpt.value ? parseInt(selectedSaidaOpt.dataset.ordem) : null;
            const ordemChegada = selectedChegadaOpt && selectedChegadaOpt.value ? parseInt(selectedChegadaOpt.dataset.ordem) : null;

            // 1. Filtra as opções do Select de Desembarque (não podem ser <= à ordem de Saída)
            Array.from(selectChegada.options).forEach(opt => {
                if (!opt.value) return;
                const ordemOpt = parseInt(opt.dataset.ordem);
                if (ordemSaida !== null && ordemOpt <= ordemSaida) {
                    opt.disabled = true;
                    opt.innerText = opt.innerText.replace(' (Inválido - Anterior)', '') + ' (Inválido - Anterior)';
                } else {
                    opt.disabled = false;
                    opt.innerText = opt.innerText.replace(' (Inválido - Anterior)', '');
                }
            });

            // 2. Filtra as opções do Select de Embarque (não podem ser >= à ordem de Chegada)
            Array.from(selectSaida.options).forEach(opt => {
                if (!opt.value) return;
                const ordemOpt = parseInt(opt.dataset.ordem);
                if (ordemChegada !== null && ordemOpt >= ordemChegada) {
                    opt.disabled = true;
                    opt.innerText = opt.innerText.replace(' (Inválido - Posterior)', '') + ' (Inválido - Posterior)';
                } else {
                    opt.disabled = false;
                    opt.innerText = opt.innerText.replace(' (Inválido - Posterior)', '');
                }
            });
        }

        // Ação ao clicar no Popup dentro do Mapa
        function definirPonto(pontoId, tipo) {
            const selectSaida = document.getElementById('ponto_de_parada_saida_id');
            const selectChegada = document.getElementById('ponto_de_parada_chegada_id');

            const pontoClicado = marcadores[pontoId];
            if (!pontoClicado) return;

            const ordemClicada = pontoClicado.ordem;

            if (tipo === 'saida') {
                const chegadaOpt = selectChegada.options[selectChegada.selectedIndex];
                const ordemChegada = chegadaOpt && chegadaOpt.value ? parseInt(chegadaOpt.dataset.ordem) : null;

                if (ordemChegada !== null && ordemClicada >= ordemChegada) {
                    alert('Erro: O ponto de embarque deve ser anterior ao ponto de desembarque selecionado.');
                    return;
                }
                selectSaida.value = pontoId;
            } else if (tipo === 'chegada') {
                const saidaOpt = selectSaida.options[selectSaida.selectedIndex];
                const ordemSaida = saidaOpt && saidaOpt.value ? parseInt(saidaOpt.dataset.ordem) : null;

                if (ordemSaida !== null && ordemClicada <= ordemSaida) {
                    alert('Erro: O ponto de desembarque não pode ser anterior ou igual ao ponto de embarque.');
                    return;
                }
                selectChegada.value = pontoId;
            }

            map.closePopup();
            aoMudarSelect();
        }

        // Desenha trajeto e atualiza destaques no mapa
        function atualizarTrajetoEInterface() {
            const saidaId = document.getElementById('ponto_de_parada_saida_id').value;
            const chegadaId = document.getElementById('ponto_de_parada_chegada_id').value;

            const cardEmbarque = document.getElementById('card-embarque');
            const cardDesembarque = document.getElementById('card-desembarque');
            const textoEmbarque = document.getElementById('texto-embarque');
            const textoDesembarque = document.getElementById('texto-desembarque');
            const distanciaInfo = document.getElementById('distancia-info');

            // Reseta todos os ícones para cor padrão (Azul)
            Object.keys(marcadores).forEach(id => {
                const idx = marcadores[id].index;
                marcadores[id].marker.setIcon(criarIconeNumerado(idx + 1, '#4f46e5'));
            });

            // Limpa o trajeto destacado do passageiro
            if (linhaTrechoPassageiro) {
                map.removeLayer(linhaTrechoPassageiro);
                linhaTrechoPassageiro = null;
            }

            distanciaInfo.classList.add('hidden');

            // Destaque de Ponto de Embarque
            if (saidaId && marcadores[saidaId]) {
                const idx = marcadores[saidaId].index;
                marcadores[saidaId].marker.setIcon(criarIconeNumerado(idx + 1, '#10b981')); // Verde
                textoEmbarque.innerText = `${idx + 1}. ${marcadores[saidaId].descricao}`;
                cardEmbarque.className = "p-3 bg-emerald-50 border-2 border-emerald-400 rounded-lg transition-all shadow-sm";
            } else {
                textoEmbarque.innerText = "Nenhum selecionado";
                cardEmbarque.className = "p-3 bg-gray-50 border border-gray-200 rounded-lg transition-all";
            }

            // Destaque de Ponto de Desembarque
            if (chegadaId && marcadores[chegadaId]) {
                const idx = marcadores[chegadaId].index;
                marcadores[chegadaId].marker.setIcon(criarIconeNumerado(idx + 1, '#f43f5e')); // Vermelho
                textoDesembarque.innerText = `${idx + 1}. ${marcadores[chegadaId].descricao}`;
                cardDesembarque.className = "p-3 bg-rose-50 border-2 border-rose-400 rounded-lg transition-all shadow-sm";
            } else {
                textoDesembarque.innerText = "Nenhum selecionado";
                cardDesembarque.className = "p-3 bg-gray-50 border border-gray-200 rounded-lg transition-all";
            }

            // Se ambos os pontos foram selecionados corretamente
            if (saidaId && chegadaId && marcadores[saidaId] && marcadores[chegadaId]) {
                const idxInicio = marcadores[saidaId].index;
                const idxFim = marcadores[chegadaId].index;

                if (idxInicio < idxFim) {
                    const subPontos = pontosData.slice(idxInicio, idxFim + 1);

                    if (subPontos.length >= 2) {
                        const coordsStr = subPontos.map(p => `${p.lng},${p.lat}`).join(';');
                        const url = `https://router.project-osrm.org/route/v1/driving/${coordsStr}?overview=full&geometries=geojson`;

                        fetch(url)
                            .then(res => res.json())
                            .then(data => {
                                if (data.routes && data.routes.length > 0) {
                                    const route = data.routes[0];
                                    const coordsTrecho = route.geometry.coordinates.map(c => [c[1], c[0]]);

                                    linhaTrechoPassageiro = L.polyline(coordsTrecho, {
                                        color: '#4f46e5',
                                        weight: 6,
                                        opacity: 0.95
                                    }).addTo(map);

                                    map.fitBounds(linhaTrechoPassageiro.getBounds(), { padding: [60, 60] });

                                    const distanciaKm = (route.distance / 1000).toFixed(1);
                                    distanciaInfo.textContent = `Seu Trajeto: ${distanciaKm} km`;
                                    distanciaInfo.classList.remove('hidden');
                                }
                            });
                    }
                }
            }
        }
    </script>
</x-app-layout>