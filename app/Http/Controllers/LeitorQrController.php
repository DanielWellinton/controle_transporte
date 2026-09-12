<?php

namespace App\Http\Controllers;

use App\Models\Viagem;
use App\Models\Passageiro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeitorQrController extends Controller
{
    public function exibirScanner()
    {
        return view('passageiros.scanner');
    }

    public function validarQrCode(Request $request)
    {
        $request->validate([
            'codigo_qr' => 'required|string',
        ]);
    
        $codigoQr = $request->codigo_qr;
    
        // Se o valor for uma URL, extrai o UUID do final da URL
        if (filter_var($codigoQr, FILTER_VALIDATE_URL)) {
            $codigoQr = basename(parse_url($codigoQr, PHP_URL_PATH));
        }
    
        // Busca a viagem pelo QR Code
        $viagem = Viagem::where('codigo_qr', $codigoQr)->first();
    
        if (!$viagem) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code inválido ou viagem não encontrada.'
            ], 404);
        }
        
        if (!$viagem->ativo) {
            return response()->json([
                'success' => false,
                'message' => 'Esta viagem não está mais ativa.'
            ], 422);
        }

        // 2. Localiza a inscrição do passageiro nesta viagem
        $passageiro = Passageiro::where('usuario_id', Auth::id())
            ->where('viagem_id', $viagem->id)
            ->first();

        if (!$passageiro) {
            return response()->json([
                'success' => false,
                'message' => 'Você não está cadastrado nesta viagem.'
            ], 403);
        }

        // 3. Verifica se a presença já foi confirmada anteriormente
        if ($passageiro->data_hora_saida !== null) {
            return response()->json([
                'success' => true,
                'already_confirmed' => true,
                'message' => 'Embarque já realizado em ' . $passageiro->data_hora_saida->format('d/m/Y H:i') . '.'
            ]);
        }

        // 4. Registra a data/hora atual no campo data_hora_saida
        // Como a tabela não usa chave ID primária, aplicamos a atualização via query builder
        Passageiro::where('usuario_id', Auth::id())
            ->where('viagem_id', $viagem->id)
            ->update([
                'data_hora_saida' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Presença confirmada! Embarque registrado com sucesso.'
        ]);
    }
}