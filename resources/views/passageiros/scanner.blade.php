<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Confirmar Embarque') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                
                <div class="text-center mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Ler QR Code da Viagem</h3>
                    <p class="text-xs text-gray-500 mt-1">Aponte a câmera para o QR Code da viagem para confirmar sua presença.</p>
                </div>

                {{-- Area da Câmera --}}
                <div id="reader-container" class="overflow-hidden rounded-xl border-2 border-dashed border-gray-300 mb-6 bg-black min-h-[260px] flex items-center justify-center">
                    <div id="reader" class="w-full"></div>
                </div>

                {{-- Feedback de Mensagem --}}
                <div id="feedback-mensagem" class="hidden p-4 rounded-lg text-xs font-bold mb-4 text-center"></div>

                {{-- Ações --}}
                <div class="space-y-2">
                    <button id="btn-reiniciar" onclick="reiniciarScanner()" class="hidden w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-lg uppercase tracking-wider shadow transition cursor-pointer">
                        Escanear Novamente
                    </button>

                    <a href="{{ route('passageiros.index') }}" class="w-full py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-lg uppercase tracking-wider text-center no-underline border border-gray-300 block">
                        Voltar para Viagens
                    </a>
                </div>

            </div>
        </div>
    </div>

    {{-- Script HTML5-QRCode --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script>
        let html5QrcodeScanner = null;
        let isProcessing = false;

        function inicializarScanner() {
            html5QrcodeScanner = new Html5Qrcode("reader");
            
            const config = { 
                fps: 10, 
                qrbox: { width: 220, height: 220 } 
            };

            html5QrcodeScanner.start(
                { facingMode: "environment" }, 
                config, 
                onScanSuccess
            ).catch(err => {
                exibirFeedback('Erro ao acessar a câmera: ' + err, 'erro');
            });
        }

        function onScanSuccess(decodedText) {
            if (isProcessing) return;
            isProcessing = true;

            html5QrcodeScanner.stop().then(() => {
                enviarValidacao(decodedText);
            }).catch(() => {
                enviarValidacao(decodedText);
            });
        }

        function enviarValidacao(codigoQr) {
            exibirFeedback('Registrando embarque...', 'info');

            fetch("{{ route('passageiros.scanner.validar') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ codigo_qr: codigoQr })
            })
            .then(response => response.json().then(data => ({ status: response.status, body: data })))
            .then(res => {
                if (res.body.success) {
                    exibirFeedback(res.body.message, 'sucesso');
                } else {
                    exibirFeedback(res.body.message || 'Falha ao registrar embarque.', 'erro');
                }
                document.getElementById('btn-reiniciar').classList.remove('hidden');
            })
            .catch(() => {
                exibirFeedback('Erro de conexão com o servidor.', 'erro');
                document.getElementById('btn-reiniciar').classList.remove('hidden');
            });
        }

        function exibirFeedback(mensagem, tipo) {
            const el = document.getElementById('feedback-mensagem');
            el.classList.remove('hidden', 'bg-emerald-100', 'text-emerald-800', 'bg-rose-100', 'text-rose-800', 'bg-blue-100', 'text-blue-800');

            if (tipo === 'sucesso') {
                el.classList.add('bg-emerald-100', 'text-emerald-800');
            } else if (tipo === 'erro') {
                el.classList.add('bg-rose-100', 'text-rose-800');
            } else {
                el.classList.add('bg-blue-100', 'text-blue-800');
            }

            el.innerText = mensagem;
        }

        function reiniciarScanner() {
            isProcessing = false;
            document.getElementById('feedback-mensagem').classList.add('hidden');
            document.getElementById('btn-reiniciar').classList.add('hidden');
            inicializarScanner();
        }

        document.addEventListener("DOMContentLoaded", function () {
            inicializarScanner();
        });
    </script>
</x-app-layout>