<?php

return [
    'host' => env('MAIL_HOST', ''),
    'port' => env('MAIL_PORT', 587),
    'username' => env('MAIL_USERNAME', ''),
    'password' => env('MAIL_PASSWORD', ''),
    'encryption' => env('MAIL_ENCRYPTION', 'tls'),
    'from_address' => env('MAIL_FROM_ADDRESS', 'noreply@ferramentasfacil.com.br'),
    'from_name' => env('MAIL_FROM_NAME', 'FerramentasFácil'),
];
