<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class espStoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tegangan' => 'required|decimal:2',
            'daya' => 'required|decimal:1',

            'debit' => 'sometimes|required|decimal:2',
            'volume' => 'sometimes|required|decimal:2',

            'suhu' => 'required|decimal:1',
            'intensitas_cahaya' => 'required|integer'
        ];
    }
}
