<?php

use kartik\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model \app\models\RoomGroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Компании';
?>
<div class="vks-company-index">

    <?= Html::pageHeader($this->title) ?>

    <?= Html::a('<span class="glyphicon glyphicon-plus"></span>', ['create'], ['class' => 'btn btn-sm btn-default']) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $model,
        'columns' => [
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
                'contentOptions' => ['width' => '1px;']
            ],
            'name',
            'address:ntext',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'contentOptions' => ['width' => '1px;']
            ]
        ],
    ]); ?>

</div>
