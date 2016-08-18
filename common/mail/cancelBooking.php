<?php
/**
 * oskr-portal
 * Created: 28.07.16 10:17
 * @copyright Copyright (c) 2016 OSKR NIAEP
 * @var $request \frontend\models\vks\RequestForm
 * @var $participant \common\models\vks\Participant
 */
use yii\helpers\Html;

$organizer = $request->owner; ?>
<p>Здравствуйте!</p>

<p>Уведомляем Вас, что совещание на тему "<?= $request->topic ?>", в котором участвует помещение
    <b><?= $participant->name ?> <?= $participant->company->name ?></b> <?= Yii::$app->formatter->asDate($request->date->sec, 'long') ?>
    c <?= $request->beginTimeString ?> до <?= $request->endTimeString ?> <b>отменено</b>.
</p>
<p>Организатор совещания <b><?= $organizer->fullName ?></b> <?= $organizer->post ?><br>
    Email: <?= Html::a($organizer->email, 'mailto:' . $organizer->email) ?>, Телефон: <?= $organizer->phone ?></p>