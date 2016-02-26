<?php
/* @var $this yii\web\View */
/* @var $model \app\models\RoomForm */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Помещения', 'url' => \yii\helpers\Url::to(['room/index'])];
$this->params['breadcrumbs'][] = $this->title; ?>
<div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
