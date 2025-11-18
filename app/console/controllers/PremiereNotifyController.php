<?php

declare(strict_types=1);

namespace app\console\controllers;

use app\application\usecases\NotifyUpcomingPremieresHandler;
use DateTimeImmutable;
use DateTimeZone;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\console\Response;

final class PremiereNotifyController extends Controller
{
    public function __construct(
        $id,
        $module,
        private readonly NotifyUpcomingPremieresHandler $handler,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actionSend(): int
    {
        $now = new DateTimeImmutable('now', new DateTimeZone('UTC'));
        $count = $this->handler->handle($now);

        $this->stdout(Yii::t('app', 'Dispatched {count} notifications.', ['count' => $count]) . PHP_EOL, Response::OUTPUT_NORMAL);

        return ExitCode::OK;
    }
}
