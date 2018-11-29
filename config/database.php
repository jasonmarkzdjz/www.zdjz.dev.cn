<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB__DEFAULT_CONNECTION', 'db_vr_merchant'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_MERCHANT_HOST','127.0.0.1'),
            'port' => env('DB_MERCHANT_PORT','3306'),
            'username' => env('DB_MERCHANT_USERNAME','root'),
            'password' => env('DB_MERCHANT_PASSWORD','vr123456'),
            'unix_socket' => env('DB_SOCKET'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'prefix' => env('DB_PREFIX','vr_'),
            'strict' => true,
            'engine' => null,
        ],

        'db_vr_merchant' => [
            'driver' => 'mysql',
            'host' => env('DB_MERCHANT_HOST','127.0.0.1'),
            'port' => env('DB_MERCHANT_PORT','3306'),
            'database' => env('DB_MERCHANT_DATABASE','db_vr_merchant'),
            'username' => env('DB_MERCHANT_USERNAME','root'),
            'password' => env('DB_MERCHANT_PASSWORD','vr123456'),
            'unix_socket' => env('DB_SOCKET'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'prefix' => env('DB_PREFIX','vr_'),
            'strict' => true,
            'engine' => null,
        ],

        'db_vr_common' => [
            'driver' => 'mysql',
            'host' => env('DB_COMMON_HOST','127.0.0.1'),
            'port' => env('DB_COMMON_PORT','3306'),
            'database' => env('DB_COMMON_DATABASE','db_vr_common'),
            'username' => env('DB_COMMON_USERNAME','root'),
            'password' => env('DB_COMMON_PASSWORD','vr123456'),
            'unix_socket' => env('DB_SOCKET'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'prefix' => env('DB_PREFIX','vr_'),
            'strict' => true,
            'engine' => null,
        ],

        'db_vr_panorama' => [
            'driver' => 'mysql',
            'host' => env('DB_PANORMA_HOST','127.0.0.1'),
            'port' => env('DB_PANORMA_PORT','3306'),
            'database' => env('DB_PANORMA_DATABASE','db_vr_panorama'),
            'username' => env('DB_PANORMA_USERNAME','root'),
            'password' => env('DB_PANORMA_PASSWORD','vr123456'),
            'unix_socket' => env('DB_SOCKET'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'prefix' => env('DB_PREFIX','vr_'),
            'strict' => true,
            'engine' => null,
            ],
        'db_vr_ucenter' => [
            'driver' => 'mysql',
            'host' => env('DB_UCENTER_HOST','127.0.0.1'),
            'port' => env('DB_UCENTER_PORT','3306'),
            'database' => env('DB_UCENTER_DATABASE','db_vr_ucenter'),
            'username' => env('DB_UCENTER_USERNAME','root'),
            'password' => env('DB_UCENTER_PASSWORD','vr123456'),
            'unix_socket' => env('DB_SOCKET'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'prefix' => env('DB_PREFIX','vr_'),
            'strict' => true,
            'engine' => null,
        ],
        'db_vr_trademall' => [
            'driver' => 'mysql',
            'host' => env('DB_TRADEMALL_HOST','127.0.0.1'),
            'port' => env('DB_TRADEMALL_PORT','3306'),
            'database' => env('DB_TRADEMALL_DATABASE','db_vr_trademall'),
            'username' => env('DB_TRADEMALL_USERNAME','root'),
            'password' => env('DB_TRADEMALL_PASSWORD','vr123456'),
            'unix_socket' => env('DB_SOCKET'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'prefix' => env('DB_PREFIX','vr_'),
            'strict' => true,
            'engine' => null,
        ],
        'db_vr_activity' => [
            'driver' => 'mysql',
            'host' => env('DB_ACTIVITY_HOST','127.0.0.1'),
            'port' => env('DB_ACTIVITY_PORT','3306'),
            'database' => env('DB_ACTIVITY_DATABASE','db_vr_activity'),
            'username' => env('DB_ACTIVITY_USERNAME','root'),
            'password' => env('DB_ACTIVITY_PASSWORD','vr123456'),
            'unix_socket' => env('DB_SOCKET'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'prefix' => env('DB_PREFIX','vr_'),
            'strict' => true,
            'engine' => null,
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => 'predis',

        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => 0,
        ],

    ],

];
