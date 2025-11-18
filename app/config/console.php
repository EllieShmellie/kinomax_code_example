<?php

declare(strict_types=1);

use app\console\controllers\PremiereNotifyController;
use yii\caching\FileCache;
use yii\console\controllers\MigrateController;
use yii\helpers\ArrayHelper;
use yii\log\FileTarget;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$mailer = require __DIR__ . '/mailer.php';

$baseConfig = [
    'id' => 'kinomax-console',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'app\console\controllers',
    'bootstrap' => ['log'],
    'components' => [
        'db' => $db,
        'mailer' => $mailer,
        'cache' => [
            'class' => FileCache::class,
        ],
        'log' => [
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'controllerMap' => [
        'premiere-notify' => PremiereNotifyController::class,
        'migrate' => [
            'class' => MigrateController::class,
            'migrationPath' => [
                '@app/migrations',
            ],
        ],
    ],
    'container' => [],
    'params' => $params,
];

return ArrayHelper::merge($baseConfig, require __DIR__ . '/notification.php');
