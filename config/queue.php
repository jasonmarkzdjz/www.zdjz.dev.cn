<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Queue Connection Name
    |--------------------------------------------------------------------------
    |
    | Laravel's queue API supports an assortment of back-ends via a single
    | API, giving you convenient access to each back-end using the same
    | syntax for every one. Here you may define a default connection.
    |
    */

    'default' => env('QUEUE_DRIVER', 'sync'),

    /*
    |--------------------------------------------------------------------------
    | Queue Connections
    |--------------------------------------------------------------------------
    |
    | Here you may configure the connection information for each server that
    | is used by your application. A default configuration has been added
    | for each back-end shipped with Laravel. You are free to add more.
    |
    | Drivers: "sync", "database", "beanstalkd", "sqs", "redis", "null"
    |
    */

    'connections' => [
        'sync' => [
            'driver' => 'sync',
        ],
        'database' => [
            'driver' => 'database',
            'table' => 'jobs',
            'queue' => 'default',
            'retry_after' => 90,
        ],
        'beanstalkd' => [
            'driver' => 'beanstalkd',
            'host' => 'localhost',
            'queue' => 'default',
            'retry_after' => 90,
        ],
        'sqs' => [
            'driver' => 'sqs',
            'key' => env('SQS_KEY', 'your-public-key'),
            'secret' => env('SQS_SECRET', 'your-secret-key'),
            'prefix' => env('SQS_PREFIX', 'https://sqs.us-east-1.amazonaws.com/your-account-id'),
            'queue' => env('SQS_QUEUE', 'your-queue-name'),
            'region' => env('SQS_REGION', 'us-east-1'),
        ],
        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => 'default',
            'retry_after' => 90,
            'block_for' => null,
        ],
        'rabbitmq' => [
            'driver'                => 'rabbitmq',
            'host'                  => env('RABBITMQ_HOST', '127.0.0.1'),
            'port'                  => env('RABBITMQ_PORT', 5672),
            'vhost'                 => env('RABBITMQ_VHOST', '/'),
            'login'                 => env('RABBITMQ_LOGIN', 'guest'),
            'password'              => env('RABBITMQ_PASSWORD', 'guest'),
            'queue'                 => env('RABBITMQ_QUEUE'), // name of the default queue,
            'exchange_declare'      => env('RABBITMQ_EXCHANGE_DECLARE', true), // create the exchange if not exists
            'queue_declare_bind'    => env('RABBITMQ_QUEUE_DECLARE_BIND', true), // create the queue if not exists and bind to the exchange
            'queue_params'          => [
                'passive'           => env('RABBITMQ_QUEUE_PASSIVE', false),//是否检测队列是否存在
                'durable'           => env('RABBITMQ_QUEUE_DURABLE', true),//对垒是否持久化
                'exclusive'         => env('RABBITMQ_QUEUE_EXCLUSIVE', false),//是否排他队列
                'auto_delete'       => env('RABBITMQ_QUEUE_AUTODELETE', false),//是否自动删除
            ],
            'exchange_params' => [
                'name'        => env('RABBITMQ_EXCHANGE_NAME', null),//交换器名称
                'type'        => env('RABBITMQ_EXCHANGE_TYPE', 'direct'), // 交换类型more info at http://www.rabbitmq.com/tutorials/amqp-concepts.html
                'passive'     => env('RABBITMQ_EXCHANGE_PASSIVE', false),//检测交换器是否存在  存在返回true 不存在返回 error
                'durable'     => env('RABBITMQ_EXCHANGE_DURABLE', true), // 是否持久化 the exchange will survive server restarts
                'auto_delete' => env('RABBITMQ_EXCHANGE_AUTODELETE', false),//是否自动删
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Failed Queue Jobs
    |--------------------------------------------------------------------------
    |
    | These options configure the behavior of failed queue job logging so you
    | can control which database and table are used to store the jobs that
    | have failed. You may change them to any database / table you wish.
    |
    */

    'failed' => [
        'database' => env('DB_CONNECTION', 'mysql'),
        'table' => 'failed_jobs',
    ],

];
