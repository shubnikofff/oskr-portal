<?php
/**
 * teleport
 * Created: 16.10.15 10:32
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */


use kartik\helpers\Html;

/**
 * @var $this \yii\web\View
 * @var $model \frontend\models\vks\Request
 */
$this->title = "Бронирование помещений"
?>

<div class="vks-request-create">

    <?= Html::pageHeader($this->title, 'заявка №' . $model->number) ?>

    <?= $this->render('_form', [
        'model' => $model,
        'submitText' => 'Сохранить'
    ]) ?>

</div>