<?php

namespace App\Http\Requests;

use App\Models\PontoDeParada;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePassageiroRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ponto_de_parada_saida_id' => [
                'required',
                Rule::exists(PontoDeParada::class, 'id'),
            ],
            'ponto_de_parada_chegada_id' => [
                'required',
                'different:ponto_de_parada_saida_id',
                Rule::exists(PontoDeParada::class, 'id'),
            ],
        ];
    }
}
