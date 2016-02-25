<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\RoomGroup;
use common\components\helpers\ViewHelper;

/* @var $this yii\web\View */
/* @var $model \app\models\RoomSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Переговорные комнаты';
?>
<div class="vks-room-index">

    <div class="page-header"><h2><?= $this->title ?></h2></div>

    <?= Html::a('<span class="glyphicon glyphicon-plus"></span>', ['create'], ['class' => 'btn btn-sm btn-default']) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $model,
        'columns' => [
            'name',
            [
                'filter' => \app\models\RoomForm::groupItems(),
                'attribute' => 'groupId',
                'value' => 'group.name'
            ],
            'ipAddress',
            'description',

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['width' => '70px;']
            ]
        ],
    ]); ?>

</div>
