<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\vks\MCU */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'MCU', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mcu-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Обновить', ['update', 'id' => (string)$model->_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => (string)$model->_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены что хотите удалить этот MCU?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            '_id',
            'name',
            'prefix',
            'internalIp',
            'externalIp',
            'description',
        ],
    ]) ?>

</div>
