<?php

use yii\helpers\Html;
use yii\grid\ActionColumn;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Типы аудиозаписи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="audio-record-type-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Новый тип', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= \yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,

        'columns' => [
            [
                'class' => ActionColumn::className(),
                'template' => '{update}',
                'contentOptions' => ['width' => '1px;']
            ],

            '_id',
            'name',

            [
                'class' => ActionColumn::className(),
                'template' => '{delete}',
                'contentOptions' => ['width' => '1px;']
            ],
        ]
    ]) ?>
</div>
