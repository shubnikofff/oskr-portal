<?php
/**
 * oskr-portal
 * Created: 30.11.2017 12:39
 * @copyright Copyright (c) 2017 OSKR NIAEP
 */

use yii\grid\GridView;
use yii\helpers\Html;

/**
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
$this->title = 'Заявки без оценки'
?>
<div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,

        'columns' => [
            [
                'attribute' => 'date',
                'content' => function ($model) {
                    return Yii::$app->formatter->asDate($model->date->toDateTime(), 'long');
                }
            ],
            [
                'attribute' => 'topic',
                'content' => function ($model) {
                    return Html::a($model->topic, ['/vks-request/view', 'id' => (string)$model->primaryKey]);
                },
            ],
            [
                'content' => function ($model) {
                    return Html::a('Оценить', ['/feed-back', 'requestId' => (string)$model->primaryKey], ['class' => 'btn btn-warning']);
                }
            ]
        ]
    ]) ?>
</div>
