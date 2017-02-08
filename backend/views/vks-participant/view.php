<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model \common\models\vks\Participant */

$this->title = $model->name;
?>
<div class="vks-room-view">

    <div class="page-header"><h2><?= $this->title ?></h2></div>

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Редактировать', ['update', 'id' => (string)$model->_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-trash"></span> Удалить', ['delete', 'id' => (string)$model->_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить эту переговорную комнату ?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'shortName',
            [
                'label' => $model->getAttributeLabel('companyId'),
                'value' => $model->company->name
            ],
            'multiConference:boolean',
            'ahuConfirmation:boolean',
            'contact:ntext',
            'phone',
            'model',
            'protocol',
            'dialString',
            'gatekeeperNumber',
            'note:ntext',
        ],
    ]) ?>

</div>
