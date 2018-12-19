<?php

return [
    'use_mock_handler' => env('VIBER_USE_MOCK_HANDLER', true),
    'mock_status_code' => env('VIBER_MOCK_STATUS_CODE', 500),
    'client_config' => [
        'base_uri' => env('VIBER_BASE_URI', 'https::/viber.com'),
        'http_errors' => env('VIBER_HTTP_ERRORS', 0),
    ],
    'release_timeout' => env('VIBER_RELEASE_TIMEOUT', 10),
];
