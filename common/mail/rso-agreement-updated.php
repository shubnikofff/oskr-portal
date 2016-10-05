<?php
/**
 * oskr-portal
 * Created: 16.09.16 9:13
 * @copyright Copyright (c) 2016 OSKR NIAEP
 *
 * @var $request \frontend\models\vks\Request
 */
use yii\helpers\Html;
$link = Yii::$app->urlManager->createAbsoluteUrl(['vks-request/view', 'id' => (string)$request->_id]);
?>

<div>

    Здравствуйте!

    <p>Режимно-секртеный отдел рассмотрел Вашу заявку на организацию совещания по
        теме &laquo;<?= $request->topic ?>&raquo;.</p>

    <p>Статус согласования РСО: <b><?= $request->rsoAgreement ?></b>.</p>

    <p><?= Html::a('Подробнее о заявке', $link) ?></p>

</div>
