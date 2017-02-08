<?php
/**
 * oskr-portal
 * Created: 30.01.17 12:37
 * @copyright Copyright (c) 2017 OSKR NIAEP
 *
 * @var $meeting \frontend\models\vks\Request
 */
use yii\helpers\Html;

$link = Yii::$app->urlManager->createAbsoluteUrl(['vks-request/view', 'id' => (string)$meeting->primaryKey]);
?>

<p>Здравствуйте.</p>

<p>Ваша заявка <b>№<?= $meeting->number ?></b> согласована.</p>

<p>Более подробную информацию о заявке Вы можете получить пройдя по следующей ссылке:</p>

<p><?= Html::a(Html::encode($link), $link) ?></p>
