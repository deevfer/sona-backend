<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        'https://sona-studio.fernandovasquez.tech',
        'https://sona.fernandovasquez.tech',
        'http://localhost:5181',
        'http://127.0.0.1:8000',
        'http://localhost:5184',
        'http://localhost',
        'https://localhost',
        'capacitor://localhost',
    ],
    'allowed_headers' => ['*'],
    'exposed_headers' => ['Retry-After'],
    'supports_credentials' => false,

];