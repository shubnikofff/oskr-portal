<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 03.09.15
 * Time: 14:39
 * @var \yii\web\View $this
 * @var \yii\data\ArrayDataProvider $dataProvider
 */
use yii\grid\GridView;

$this->title = 'Привилегии'
?>

<div class="role-index">
    <div class="page-header"><h2><?= $this->title ?></h2></div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => '\yii\grid\ActionColumn',
                'template' => '{update}',
                'contentOptions' => ['width' => '1px;']
            ],
            [
                'attribute' => 'description',
                'label' => 'Название'
            ],
            [
                'attribute' => 'name',
                'label' => 'Системное имя'
            ],
            [
                'attribute' => 'ruleName',
                'label' => 'Правило',
                'content' => function($model){
                    return $model->ruleName ? $model->ruleName : '-';
                }
            ],
            [
                'attribute' => 'createdAt',
                'label' => 'Дата создания',
                'format' => 'date'
            ],
        ]
    ]) ?>
</div>
