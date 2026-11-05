<?php

namespace App\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class jamurStoreRequest extends FormRequest
{
    /**
     * Cek API Key dari header ESP (sama seperti labStoreRequest).
     */
    public function authorize(): bool
    {
        $espApiKey = $this->header('API_KEY');
        $apiKey = config('devices.esp_api_key');

        if (empty($espApiKey)) {
            return false;
        }

        return is_string($apiKey) && $apiKey !== '' && hash_equals($apiKey, $espApiKey);
    }

    /**
     * Aturan validasi data yang dikirim ESP jamur.
     * 'sometimes' = kolom boleh tidak dikirim (mis. node2 mati).
     */
    public function rules(): array
    {
        return [
            'suhu_node1'       => 'sometimes|required|decimal:1',
            'suhu_node2'       => 'sometimes|required|decimal:1',
            'kelembaban_node1' => 'sometimes|required|integer',
            'kelembaban_node2' => 'sometimes|required|integer',
            'status_relay'     => 'sometimes|required|boolean',
            'mode'             => 'sometimes|required|in:otomatis,manual',
        ];
    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException('Akses ditolak! API Key tidak valid atau tidak ditemukan.');
    }
}
