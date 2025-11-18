<?php

declare(strict_types=1);

use yii\helpers\Html;

?>
<?= Html::tag('h2', Html::encode($subject ?? 'Скоро премьера')) ?>
<p><?= Html::encode($releaseTitle ?? '') ?> выйдет <?= Html::encode($premiereAt ?? '') ?>.</p>
<p><?= Html::a('Открыть расписание', Yii::$app->urlManager->createAbsoluteUrl(['/release/view', 'title' => $releaseTitle ?? ''])) ?></p>
