<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\RoomGroup;
use common\components\helpers\ViewHelper;

/* @var $this yii\web\View */
/* @var $model \app\models\VksParticipantSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Переговорные комнаты';
?>
<div class="vks-room-index">

    <div class="page-header"><h2><?= $this->title ?></h2></div>

    <?= Html::a('<span class="glyphicon glyphicon-plus"></span>', ['create'], ['class' => 'btn btn-sm btn-default']) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $model,
        'columns' => [
            'shortName',
            [
                'filter' => ViewHelper::items(RoomGroup::className(), '_id', 'name'),
                'attribute' => 'companyId',
                'value' => 'company.name'
            ],
            'ipAddress',
            'note:ntext',

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['width' => '70px;']
            ]
        ],
    ]); ?>

</div>
