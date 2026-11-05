<?php

return [
    // Keep the existing firmware header names; config remains available after
    // artisan config:cache, unlike env() calls inside request classes.
    'esp_api_key' => env('ESP_API_KEY'),
    'environment_api_key' => env('API_KEY'),
];
