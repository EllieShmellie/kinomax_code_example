<?php

declare(strict_types=1);

namespace app\presentation\controllers;

use app\application\dto\ScheduleItem;
use app\application\queries\GetScheduleQuery;
use app\application\usecases\SubscribeToPremiereHandler;
use app\presentation\forms\SubscribeForm;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

final class SiteController extends Controller
{
    public $layout = '@app/presentation/views/layouts/main';

    public function __construct(
        $id,
        $module,
        private readonly GetScheduleQuery $scheduleQuery,
        private readonly SubscribeToPremiereHandler $subscribeHandler,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['get', 'post'],
                ],
            ],
        ];
    }

    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex(): Response|string
    {
        $schedule = $this->scheduleQuery->fetch();
        $releaseOptions = $this->buildReleaseOptions($schedule);

        $subscribeForm = new SubscribeForm($this->subscribeHandler, array_keys($releaseOptions));
        if ($subscribeForm->load(Yii::$app->request->post()) && $subscribeForm->subscribe()) {
            Yii::$app->session->setFlash('success', 'Мы добавили вас в список уведомлений. Проверьте почту ближе к премьере.');
            return $this->refresh();
        }

        return $this->render('site/index', [
            'schedule' => $schedule,
            'subscribeForm' => $subscribeForm,
            'releaseOptions' => $releaseOptions,
            'timezoneOptions' => SubscribeForm::timezoneOptions(),
        ]);
    }

    public function getViewPath(): string
    {
        return Yii::getAlias('@app/presentation/views');
    }

    /**
     * @param ScheduleItem[] $schedule
     */
    private function buildReleaseOptions(array $schedule): array
    {
        $formatter = Yii::$app->formatter;
        $options = [];
        foreach ($schedule as $item) {
            $label = sprintf(
                '%s — %s',
                $item->title,
                $formatter->asDatetime($item->premiereAt, 'php:d M, H:i')
            );
            $options[$item->id] = $label;
        }

        return $options;
    }
}
