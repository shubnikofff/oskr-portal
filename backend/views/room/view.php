<?php

use yii\widgets\DetailView;
use kartik\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \common\models\Room */

$this->title = "Комната &laquo;{$model->name}&raquo;"; ?>

<div>

    <?= Html::pageHeader($this->title) ?>

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Редактировать', ['update', 'id' => (string)$model->primaryKey], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-trash"></span> Удалить', ['delete', 'id' => (string)$model->primaryKey], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить эту переговорную комнату ?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="row col-lg-6">

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'name',
                'description',
                [
                    'attribute' => 'groupId',
                    'value' => $model->group->name
                ],
                'bookingAgreement:boolean',
                'contactPerson:ntext',
                'phone',
                'equipment',
                'ipAddress'
            ],
        ]) ?>

    </div>

</div>
