<?php
/* @var $this yii\web\View */
/* @var $model \common\models\RoomGroup */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Группы помещений', 'url' => \yii\helpers\Url::to(['room-group/index'])];
$this->params['breadcrumbs'][] = $this->title; ?>

<div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
