<?php
/**
 * oskr-portal
 * Created: 25.11.16 11:04
 * @copyright Copyright (c) 2016 OSKR NIAEP
 *
 * @var $request \frontend\models\vks\Request
 * @var $participant \common\models\vks\Participant
 */
$organizer = $request->owner;
?>
Уведомляем Вас,

что <?= Yii::$app->formatter->asDate($request->date->toDateTime(), 'long') ?> c <?= $request->beginTimeString ?> по <?= $request->endTimeString ?> пройдет совещание <?= $request->mode === $request::MODE_WITH_VKS ? 'в режиме ВКС' : '' ?>

Место проеведения: <?= $participant->company->name . ' - ' . $participant->name ?>

Тема совещания: <?= $request->topic ?>

Необходимое оборудование: <?= is_array($request->equipment) ? implode(', ', $request->equipment) : '' ?>

Организатор: <?= $organizer->fullName ?> - <?= $organizer->post ?>

E-mail: <?= $organizer->email ?>

Телефон: <?= $organizer->phone ?>

Примечание: <?= $request->note ?>




Управление системных корпоративных ресурсов АО ИК "АСЭ"