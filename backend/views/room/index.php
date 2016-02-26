<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model \app\models\RoomSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Помещения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vks-room-index">

    <?= Html::a('<span class="glyphicon glyphicon-plus"></span> Новое помещение', ['create'], ['class' => 'btn btn-success']) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $model,
        'summaryOptions' => ['class' => 'summary text-right'],
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
