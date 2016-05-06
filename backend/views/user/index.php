<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 31.08.15
 * Time: 16:32
 */
use kartik\helpers\Html;
/**
 * @var $this \yii\web\View
 * @var $searchModel \backend\models\UserSearch
 */
$this->title = "Пользователи";
?>

<div class="user-index">

    <?= Html::pageHeader($this->title) ?>

    <?= \yii\grid\GridView::widget([
        'dataProvider' => $searchModel->search(),
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => '\yii\grid\ActionColumn',
                'template' => '{update}'
            ],
            [
                'attribute' => 'username',
                'label' => 'Пользователь'
            ],
            'email',
            [
                'attribute' => 'lastName',
                'label' => 'Имя  сотрудника',
                'content' => function ($model) {
                    return $model->shortName;
                },
            ],
            [
                'attribute' => 'status',
                'filter' => [$searchModel::STATUS_ACTIVE => 'Активный', $searchModel::STATUS_BLOCKED => 'Заблокирован',],
                'contentOptions' => ['class' => 'text-center'],
                'content' => function ($model) {
                    $content = '';
                    switch ($model->status) {
                        case $model::STATUS_ACTIVE:
                            $content = '<span class="glyphicon glyphicon-ok text-success"></span>';
                            break;
                        case $model::STATUS_BLOCKED:
                            $content = '<span class="glyphicon glyphicon glyphicon-lock text-danger"></span>';
                            break;
                    }
                    return $content;
                }
            ],
            [
                'attribute' => 'createdAt',
                'content' => function($model){
                    return Yii::$app->formatter->asDate($model->createdAt->sec);
                }
            ],
            [
                'class' => '\yii\grid\ActionColumn',
                'template' => '{delete}'
            ]
        ]
    ]) ?>

</div>