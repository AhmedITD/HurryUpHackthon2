<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Broadcasting Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default broadcasting connection that will be
    | used by the framework. Laravel supports several broadcast drivers.
    |
    | Supported: "pusher", "redis", "log", "null"
    |
    */

    'default' => env('BROADCAST_DRIVER', 'log'),

    /*
    |--------------------------------------------------------------------------
    | Broadcast Connections
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the broadcast connections that will be used
    | to broadcast events to other systems or over websockets. Samples of
    | each available type of connection are provided inside this array.
    |
    */

    'connections' => [

        'pusher' => [
            'driver' => 'pusher',
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'app_id' => env('PUSHER_APP_ID'),
            'options' => [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true,
            ],
        ],

        // Example of using Redis for broadcasting:
        /*
        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ],
        */

        // Example of logging broadcasts:
        /*
        'log' => [
            'driver' => 'log',
        ],
        */

        // You can define more connections here as needed.
    ],

];
