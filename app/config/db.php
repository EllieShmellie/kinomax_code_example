<?php

declare(strict_types=1);

return [
    'class' => yii\db\Connection::class,
    'dsn' => sprintf(
        'pgsql:host=%s;port=%s;dbname=%s',
        getenv('POSTGRES_HOST') ?: 'db',
        getenv('POSTGRES_DB_PORT') ?: '5432',
        getenv('POSTGRES_DB') ?: 'kinomax'
    ),
    'username' => getenv('POSTGRES_USER') ?: 'kinomax',
    'password' => getenv('POSTGRES_PASSWORD') ?: 'kinomax_pass',
    'charset' => 'utf8',
    'schemaMap' => [
        'pgsql' => [
            'class' => yii\db\pgsql\Schema::class,
            'defaultSchema' => 'public',
        ],
    ],
];
