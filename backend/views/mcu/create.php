<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\vks\MCU */

$this->title = 'Создать MCU';
$this->params['breadcrumbs'][] = ['label' => 'MCU', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mcu-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
