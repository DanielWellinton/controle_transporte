<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreViagemRequest extends FormRequest
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
            'rota_id' => ['required', 'exists:rotas,id'],
            'motorista_id' => ['required', 'exists:motoristas,id'],
            'veiculo_id' => ['required', 'exists:veiculos,id'],
            'data_hora_saida' => ['required', 'date'],
            'data_hora_chegada' => ['nullable', 'date', 'after_or_equal:data_hora_saida'],
            'ativo' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'ativo' => $this->has('ativo'),
        ]);
    }
}
