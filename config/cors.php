<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        'https://sona-studio.fernandovasquez.tech',
        'https://sona.fernandovasquez.tech',
    ],
    'allowed_headers' => ['*'],
    'exposed_headers' => ['Retry-After'],
    'supports_credentials' => false,

];