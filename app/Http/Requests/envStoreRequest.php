<?php

namespace App\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class envStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $espApiKey = $this->header('API_KEY');

        $apiKey = config('devices.environment_api_key');

        if (is_string($espApiKey) && $espApiKey !== '' && is_string($apiKey) && $apiKey !== '' && hash_equals($apiKey, $espApiKey)) {
            return true; // Cocok! Lanjut ke proses validasi rules()
        }

        return false; // Tidak cocok! Hentikan request di sini.
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'suhu' => 'required|decimal:1',
            'intensitas_cahaya' => 'required|integer'
        ];
    }

    protected function failedAuthorization()
    {
        // Melempar exception dengan pesan kustom
        throw new AuthorizationException('Akses ditolak! API Key tidak valid atau tidak ditemukan.');
    }
}
