<?php
declare(strict_types=1);

use EoneoPay\Externals\ORM\Subscribers\ValidateEventSubscriber;
use LaravelDoctrine\Extensions\SoftDeletes\SoftDeleteableExtension;

return [
    /*
    |--------------------------------------------------------------------------
    | Entity Mangers
    |--------------------------------------------------------------------------
    |
    | Configure your Entity Managers here. You can set a different connection
    | and driver per manager and configure events and filters. Change the
    | paths setting to the appropriate path and replace App namespace
    | by your own namespace.
    |
    | Available meta drivers: annotations|yaml|xml|config|static_php
    |
    | Available connections: mysql|oracle|pgsql|sqlite|sqlsrv
    | (Connections can be configured in the database config)
    |
    | --> Warning: Proxy auto generation should only be enabled in dev!
    |
    */
    'managers' => [
        'default' => [
            'dev' => \env('APP_DEBUG', false),
            'meta' => \env('DOCTRINE_METADATA', 'annotations'),
            'connection' => \env('DB_CONNECTION', 'mysql'),
            'namespaces' => [
                'App\Database\Entities',
                'Gedmo\Loggable\Entity'
            ],
            'paths' => [
                \base_path('app/Database/Entities'),
                \base_path('vendor/gedmo/doctrine-extensions/lib/Gedmo/Loggable/Entity')
            ],
            'proxies' => [
                'namespace' => false,
                'path' => \storage_path('app/proxies'),
                'auto_generate' => \env('DOCTRINE_PROXY_AUTOGENERATE', false)
            ],

            /*
            |--------------------------------------------------------------------------
            | Doctrine events
            |--------------------------------------------------------------------------
            |
            | The listener array expects the key to be a Doctrine event
            | e.g. Doctrine\ORM\Events::onFlush
            |
            */
            'events' => [
                'listeners' => [],
                'subscribers' => [
                    ValidateEventSubscriber::class,
                ]
            ],
            'filters' => []
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Doctrine Extensions
    |--------------------------------------------------------------------------
    |
    | Enable/disable Doctrine Extensions by adding or removing them from the list
    |
    | If you want to require custom extensions you will have to require
    | laravel-doctrine/extensions in your composer.json
    |
    */
    'extensions' => [
        SoftDeleteableExtension::class,
        // EoneoPay\Externals\ORM\Extensions\SoftDeleteExtension::class,
        LaravelDoctrine\Extensions\Timestamps\TimestampableExtension::class
    ],

    /*
    |--------------------------------------------------------------------------
    | Doctrine custom types
    |--------------------------------------------------------------------------
    */
    'custom_types' => [
        'json' => LaravelDoctrine\ORM\Types\Json::class
    ],

    /*
    |--------------------------------------------------------------------------
    | DQL custom datetime functions
    |--------------------------------------------------------------------------
    */
    'custom_datetime_functions' => [],

    /*
    |--------------------------------------------------------------------------
    | DQL custom numeric functions
    |--------------------------------------------------------------------------
    */
    'custom_numeric_functions' => [],

    /*
    |--------------------------------------------------------------------------
    | DQL custom string functions
    |--------------------------------------------------------------------------
    */
    'custom_string_functions' => [],

    /*
    |--------------------------------------------------------------------------
    | Register custom hydrators
    |--------------------------------------------------------------------------
    */
    'custom_hydration_modes' => [
    ],

    /*
    |--------------------------------------------------------------------------
    | Enable query logging with laravel file logging,
    | debugbar, clockwork or an own implementation.
    | Setting it to false, will disable logging
    |
    | Available:
    | - LaravelDoctrine\ORM\Loggers\LaravelDebugbarLogger
    | - LaravelDoctrine\ORM\Loggers\ClockworkLogger
    | - LaravelDoctrine\ORM\Loggers\FileLogger
    |--------------------------------------------------------------------------
    */
    'logger' => \env('DOCTRINE_LOGGER', false),

    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    |
    | Configure meta-data, query and result caching here.
    | Optionally you can enable second level caching.
    |
    | Available: acp|array|file|memcached|redis
    |
    */
    'cache' => [
        'default' => \str_replace('$', '', \env('DOCTRINE_CACHE', 'file')),
        'namespace' => null,
        'second_level' => false
    ],

    /*
    |--------------------------------------------------------------------------
    | Gedmo extensions
    |--------------------------------------------------------------------------
    |
    | Settings for Gedmo extensions
    | If you want to use this you will have to require
    | laravel-doctrine/extensions in your composer.json
    |
    */
    'gedmo' => [
        'all_mappings' => false
    ]
];
