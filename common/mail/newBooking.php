<?php
/**
 * oskr-portal
 * Created: 28.07.16 10:54
 * @copyright Copyright (c) 2016 OSKR NIAEP
 * @var $request \frontend\models\vks\RequestForm
 * @var $participant \common\models\vks\Participant
 */
use yii\helpers\Html;

$organizer = $request->owner; ?>
<p>Здравствуйте!</p>

<p>Уведомляем Вас, что помещение <b><?= $participant->name ?> <?= $participant->company->name ?></b> забронировано на
    <b><?= Yii::$app->formatter->asDate($request->date->sec, 'long') ?></b> c <b><?= $request->beginTimeString ?></b> до
    <b><?= $request->endTimeString ?></b> для проведения совещания на тему "<?= $request->topic ?>"
</p>
<p>Организатор совещания <b><?= $organizer->fullName ?></b> <?= $organizer->post ?><br>
    Email: <?= Html::a($organizer->email, 'mailto:' . $organizer->email) ?>, Телефон: <?= $organizer->phone ?></p>