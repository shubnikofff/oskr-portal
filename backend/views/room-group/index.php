<?php

use kartik\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel \app\models\RoomGroupSearch */

$this->title = 'Группы помещений';
$this->params['breadcrumbs'][] = $this->title; ?>

<div>

    <?= Html::a('<span class="glyphicon glyphicon-plus"></span> Новая группа', ['create'], ['class' => 'btn btn-success']) ?>

    <?= GridView::widget([
        'dataProvider' => $searchModel->search(),
        'filterModel' => $searchModel,
        'summaryOptions' => ['class' => 'summary text-right'],
        'columns' => [
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
                'contentOptions' => ['width' => '1px;']
            ],
            'name',
            'abbreviation',
            'description:ntext',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'contentOptions' => ['width' => '1px;']
            ]
        ],
    ]); ?>

</div>
