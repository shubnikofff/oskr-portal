<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 03.09.15
 * Time: 10:46
 * @var \yii\web\View $this
 * @var \yii\data\ArrayDataProvider $dataProvider
 */
use yii\grid\GridView;
use yii\helpers\Html;
$this->title = "Роли пользователей";
?>

<div class="role-index">

    <div class="page-header"><h2><?= $this->title ?></h2></div>

    <?= Html::a('<span class="glyphicon glyphicon-plus"></span>', ['create'], ['class' => 'btn btn-sm btn-default']) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => '\yii\grid\ActionColumn',
                'template' => '{update}',
                'contentOptions' => ['width' => '1px;']
            ],
            [
                'attribute' => 'name',
                'label' => 'Название'
            ],
            [
                'attribute' => 'description',
                'label' => 'Описание'
            ],
            [
                'attribute' => 'createdAt',
                'label' => 'Дата создания',
                'format' => 'date'
            ],
            [
                'class' => '\yii\grid\ActionColumn',
                'template' => '{delete}',
                'contentOptions' => ['width' => '1px;']
            ]
        ]
    ]) ?>
</div>
