<?php
/**
 * oskr.local
 * Created: 17.05.16 10:26
 * @copyright Copyright (c) 2016 OSKR NIAEP
 *
 * @var $room \common\models\vks\Participant
 * @var $request \frontend\models\vks\Request
 */
use yii\helpers\Html;
use common\components\MinuteFormatter;

$link = Yii::$app->urlManager->createAbsoluteUrl(['vks-request/approve-booking', 'roomId' => (string)$room->_id, 'requestId' => (string)$request->_id]);
?>
<p>Здравствуйте!</p>

<p>Вы получили это письмо, потому что на <?= Yii::$app->name ?> была забронирована комната <b><?= $room->name ?></b>,
    которая входит в зону Вашей компетенции.</p>

<p>Данная комната была забронирована в рамках проведения совещнаия на тему: &laquo;<?= $request->topic ?>&raquo;,
    которое будет проходить <b><?= Yii::$app->formatter->asDate($request->date->toDateTime(), 'long') ?></b> c
    <b><?= MinuteFormatter::asString($request->beginTime) ?></b> до
    <b><?= MinuteFormatter::asString($request->endTime) ?></b>.</p>

<p>Вам необходимо согласовать это бронирование пройдя по следующей ссылке:</p>

<p><?= Html::a(Html::encode($link), $link) ?></p>