<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\vks\MCU */

$this->title = 'Update MCU: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'MCU', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => (string)$model->_id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="mcu-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
