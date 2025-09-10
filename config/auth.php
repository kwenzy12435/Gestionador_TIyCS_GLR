<?php

return [
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'usuarios_ti',
    ],
],

    'providers' => [
    'usuarios_ti' => [
        'driver' => 'eloquent',
        'model' => App\Models\UsuarioTI::class,
    ],
],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];