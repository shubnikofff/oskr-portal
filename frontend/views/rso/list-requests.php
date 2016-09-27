<?php
/**
 * oskr-portal
 * Created: 20.09.16 16:08
 * @copyright Copyright (c) 2016 OSKR NIAEP
 *
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
use yii\helpers\Html;

echo \yii\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    'tableOptions' => [
        'class' => 'table table-striped',
    ],
    'columns' => [
        [
            'attribute' => 'topic',
            'content' => function ($model) {
                return Html::a($model->topic, ['/vks-request/view', 'id' => (string)$model->primaryKey]);
            },
        ],
        [
            'attribute' => 'date',
            'content' => function($model) {
                return Yii::$app->formatter->asDate($model->date->sec, 'long');
            },
            'contentOptions' => [
                'style' => 'white-space: nowrap'
            ]
        ],
        'rsoAgreement'
    ]
]);