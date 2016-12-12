<?php
/**
 * oskr-portal
 * Created: 25.11.16 11:03
 * @copyright Copyright (c) 2016 OSKR NIAEP
 *
 * @var $request \frontend\models\vks\Request
 * @var $participant \common\models\vks\Participant
 */
?>
Доброго времени суток!


Уведомляем Вас об отмене совещания, которое должно было проходить <?= Yii::$app->formatter->asDate($request->date->sec, 'long') ?> c <?= $request->beginTimeString ?> по <?= $request->endTimeString ?> в <?= $participant->company->name . ' - ' . $participant->name ?>.
Причина отмены: <?= $request->cancellationReason ?>.




Управление системных корпоративных ресурсов АО ИК "АСЭ"