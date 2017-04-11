<?php
/**
 * oskr-portal
 * Created: 01.02.17 18:48
 * @copyright Copyright (c) 2017 OSKR NIAEP
 *
 * @var $request \frontend\models\vks\Request
 */
use yii\helpers\Html;

$link = Yii::$app->urlManager->createAbsoluteUrl(['vks-request/view', 'id' => (string)$request->primaryKey]);
?>

<p>Здравствуйте.</p>

<p>Ваша заявка <b>№<?= $request->number ?> на <?= Yii::$app->formatter->asDate($request->date->toDateTime(), 'long') ?></b> была отменена.</p>

<p>Причина отмены: <?= $request->cancellationReason ?>.</p>

<p>Более подробную информацию о заявке Вы можете получить пройдя по следующей ссылке:</p>

<p><?= Html::a(Html::encode($link), $link) ?></p>