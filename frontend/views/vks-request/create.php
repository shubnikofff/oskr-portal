<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 07.10.15
 * Time: 14:39
 */

use kartik\helpers\Html;

/**
 * @var $this \yii\web\View
 * @var $model \frontend\models\vks\Request
 */
$this->title = "Бронирование помещений"
?>

<div class="vks-request-create">

    <?= Html::pageHeader($this->title) ?>

    <?= $this->render('_form', [
        'model' => $model,
        'submitText' => 'Отправить заявку',
    ]) ?>

</div>