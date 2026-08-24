<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Escanear QR Code do Veículo</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8 space-y-4 text-center">
            <div class="bg-white p-6 rounded-xl shadow-sm border">
                <p class="text-sm text-gray-600 mb-4">Aponte a câmera para o QR Code no veículo para confirmar seu embarque.</p>
                
                <div id="reader" class="w-full rounded-lg overflow-hidden border"></div>
            </div>
            
            <a href="{{ route('passageiro.dashboard') }}" class="inline-block text-sm text-gray-600 font-semibold hover:underline">Voltar ao Painel</a>
        </div>
    </div>

    <!-- Biblioteca JS para Leitura de QR Code -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        function onScanSuccess(decodedText, decodedResult) {
            // decodedText conterá a URL ou o código lido
            window.location.href = decodedText;
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", { fps: 10, qrbox: { width: 250, height: 250 } }, false);
        html5QrcodeScanner.render(onScanSuccess);
    </script>
</x-app-layout>