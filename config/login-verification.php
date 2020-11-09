<?php

return [
    'database' => [
        'connection' => '',
        'table_name' => 'login_verifications',
    ],

    'routes' => [
        'send' => '/send',
        'confirm' => '/confirm',
    ],

    'views' => [
        'confirm' => 'login-verification::confirm',
        'mail' => 'login-verification::mail',
    ],
];
