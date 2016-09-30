<?php
/**
 * oskr-portal
 * Created: 15.09.16 16:16
 * @copyright Copyright (c) 2016 OSKR NIAEP
 *
 * @var $request \frontend\models\vks\Request
 */
use yii\helpers\Html;

?>

<div>

    Здравствуйте!

    <p>На <b><?= Yii::$app->name ?></b> была зарегистрирована заявка на совещание с участием иностранных организаций.
    </p>

    <p><?= Html::a('Подробнее о заявке', ['/vks-request/view', 'id' => (string)$request->_id]) ?></p>

</div>
