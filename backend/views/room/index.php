<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel \app\models\RoomSearch */

$this->title = 'Помещения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vks-room-index">

    <?= Html::a('<span class="glyphicon glyphicon-plus"></span> Новое помещение', ['create'], ['class' => 'btn btn-success']) ?>

    <?= \yii\grid\GridView::widget([
        'dataProvider' => $searchModel->search(),
        'filterModel' => $searchModel,
        'summaryOptions' => ['class' => 'summary text-right'],
        'columns' => [
            'name',
            [
                'attribute' => 'groupId',
                'filter' => \app\models\RoomForm::groupItems(),
                'value' => 'group.name'
            ],
            [
                'attribute' => 'bookingAgreement',
                'format' => 'boolean',
                'filter' => ['1' => 'Необходимо согласование'],
                'label' => 'Согласование'
            ],
            [
                'attribute' => 'multipleBooking',
                'format' => 'boolean',
                'filter' => ['1' => 'Одновременное броинрование'],
                'label' => 'Мультибронирование'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['width' => '70px;']
            ]
        ],
    ]); ?>

</div>
