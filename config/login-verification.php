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

    'confirm_view' => '',

    'mail_view' => 'login-verification::mail',
];
