<?php

/** @var yii\web\View $this */
/** @var app\application\dto\ScheduleItem[] $schedule */
/** @var app\presentation\forms\SubscribeForm $subscribeForm */
/** @var array $releaseOptions */
/** @var array $timezoneOptions */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Расписание премьер';
?>

<div class="site-index py-5">
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0">Ближайшие показы</h4>
                </div>
                <?php if (empty($schedule)): ?>
                    <div class="card-body">
                        <p class="text-muted mb-0">Расписание пустое — добавьте события и обновите страницу.</p>
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush" id="schedule">
                        <?php foreach ($schedule as $item): ?>
                            <div class="list-group-item py-4">
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                    <div>
                                        <h5 class="mb-1"><?= Html::encode($item->title) ?></h5>
                                        <div class="text-muted small">
                                            Премьера <?= Yii::$app->formatter->asDatetime($item->premiereAt, 'php:d M Y, H:i') ?> UTC
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <?php if ($item->isNotified): ?>
                                            <span class="badge bg-success text-white">рассылка выполнена</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">ожидает рассылку</span>
                                        <?php endif; ?>
                                        <div class="fw-semibold mt-2"><?= $item->subscriberCount ?> подписок</div>
                                    </div>
                                </div>
                                <?php if (!empty($item->subscribers)): ?>
                                    <div class="mt-3 d-flex flex-wrap gap-2">
                                        <?php foreach ($item->subscribers as $email): ?>
                                            <span class="badge bg-light text-dark border">
                                                <?= Html::encode($email) ?>
                                            </span>
                                        <?php endforeach; ?>
                                        <?php $remaining = $item->subscriberCount - count($item->subscribers); ?>
                                        <?php if ($remaining > 0): ?>
                                            <span class="text-muted small align-self-center">+<?= $remaining ?> ещё</span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0">Подписаться на напоминание</h4>
                </div>
                <div class="card-body">
                    <?php if (empty($releaseOptions)): ?>
                        <div class="alert alert-warning">
                            Список премьер пуст — добавьте события, чтобы открыть подписку.
                        </div>
                    <?php endif; ?>
                    <?php $form = ActiveForm::begin(['options' => ['class' => 'subscribe-form']]); ?>
                    <?= $form->field($subscribeForm, 'email')->input('email', ['placeholder' => 'fan@example.com']) ?>
                    <?= $form->field($subscribeForm, 'timezone')->dropDownList($timezoneOptions, ['prompt' => 'Выберите часовой пояс']) ?>
                    <?= $form->field($subscribeForm, 'release_id')->dropDownList($releaseOptions, [
                        'prompt' => 'Какая премьера интересует?',
                        'disabled' => empty($releaseOptions),
                    ]) ?>
                    <div class="d-grid">
                        <?= Html::submitButton('Получать уведомления', [
                            'class' => 'btn btn-primary btn-lg',
                            'disabled' => empty($releaseOptions),
                        ]) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                    <p class="text-muted small mt-3 mb-0">
                        Мы присылаем одно письмо за 3 дня до старта и больше не беспокоим подписчика до следующей премьеры.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
