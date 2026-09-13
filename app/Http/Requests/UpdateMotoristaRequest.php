<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMotoristaRequest extends FormRequest
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
            'usuario_id' => [
                'required',
                'exists:users,id',
                Rule::unique('motoristas', 'usuario_id')->ignore($this->motorista),
            ],
            'cnh' => [
                'required',
                'string',
                'max:20',
                Rule::unique('motoristas', 'cnh')->ignore($this->motorista),
            ],
            'data_validade_cnh' => ['required', 'date'],
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
