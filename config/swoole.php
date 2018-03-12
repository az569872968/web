<?php

return [

    'online_listen_ip' => 'www.opcache.net',

    'online_listen_port' => 9502,

    'online_ttl' => 180,

    'redis' => [

        'cluster' => false,

        'default' => [
            'host'     => '127.0.0.1',
            'port'     => 6379,
            'database' => 0,
        ],

    ],
];