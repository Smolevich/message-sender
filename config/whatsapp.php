<?php

return [
    'use_mock_handler' => env('WHATSAPP_USE_MOCK_HANDLER', true),
    'mock_status_code' => env('WHATSAPP_MOCK_STATUS_CODE', 200),
    'client_config' => [
        'base_uri' => env('WHATSAPP_BASE_URI', 'https::/viber.com'),
        'http_errors' => env('WHATSAPP_HTTP_ERRORS', 0),
    ],
    'release_timeout' => env('WHATSAPP_RELEASE_TIMEOUT', 10),
];
