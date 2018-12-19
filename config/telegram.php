<?php

return [
    'use_mock_handler' => env('TELEGRAM_USE_MOCK_HANDLER', true),
    'mock_status_code' => env('TELEGRAM_MOCK_STATUS_CODE', 200),
    'client_config' => [
        'base_uri' => env('TELEGRAM_BASE_URI', 'https::/telegram.org'),
        'http_errors' => env('TELEGRAM_HTTP_ERRORS', 0),
    ],
    'release_timeout' => env('TELEGRAM_RELEASE_TIMEOUT', 10),
];
