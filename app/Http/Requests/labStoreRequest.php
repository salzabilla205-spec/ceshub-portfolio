<?php

namespace App\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class labStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $espApiKey = $this->header('API_KEY');

        $apiKey = config('devices.esp_api_key');

        if (empty($espApiKey)) {
            return false;
        }

        if (is_string($apiKey) && $apiKey !== '' && hash_equals($apiKey, $espApiKey)) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'lampu1' => 'sometimes|nullable|boolean',
            'lampu2' => 'sometimes|nullable|boolean',
            'tegangan' => 'required|decimal:2',
            'daya' => 'required|decimal:1',

            'suhu' => 'required|decimal:1',
            'intensitas_cahaya' => 'required|integer',
            'irradiansi' => 'sometimes|nullable|numeric|min:0|max:1000000',
            'satuan_irradiansi' => 'sometimes|in:W/m2',
        ];
    }

    protected function failedAuthorization()
    {
        // Melempar exception dengan pesan kustom
        throw new AuthorizationException('Akses ditolak! API Key tidak valid atau tidak ditemukan.');
    }
}
