<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://localhost:5180', 'http://127.0.0.1:5180'],
    'allowed_headers' => ['*'],
    'exposed_headers' => ['Retry-After'],
    'supports_credentials' => false,

];