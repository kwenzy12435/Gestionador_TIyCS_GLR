<?php
return [
    'csrf_token_expiry' => 3600, // 1 hora en segundos
    'max_login_attempts' => 5,
    'lockout_time' => 900, // 15 minutos en segundos
    'password_min_length' => 8,
    'session_name' => 'tiycs_secure_session',
    'session_lifetime' => 7200, // 2 horas en segundos
];
?>