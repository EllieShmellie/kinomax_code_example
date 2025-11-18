<?php

declare(strict_types=1);

use app\console\controllers\PremiereNotifyController;
use yii\helpers\ArrayHelper;

return ArrayHelper::merge(
    [
        'controllerMap' => [
            'premiere-notify' => PremiereNotifyController::class,
        ],
    ],
    require __DIR__ . '/dependencies.php'
);
