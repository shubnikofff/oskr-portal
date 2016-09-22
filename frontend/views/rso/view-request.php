<?php
/**
 * oskr-portal
 * Created: 21.09.16 15:58
 * @copyright Copyright (c) 2016 OSKR NIAEP
 *
 * @var $model \frontend\models\vks\Request
 */
use yii\helpers\Html;
?>

<div>

    <?= \yii\widgets\DetailView::widget([
        'model' => $model,
        'attributes' => [
            'topic',
            [
                'label' => 'Дата и время',
                'value' => Yii::$app->formatter->asDate($model->date->sec, 'long') . " с " . $model->beginTimeString . " по " . $model->endTimeString
            ],
            [
                'label' => 'Организатор',
                'value' => $model->owner->fullName . ' - ' . $model->owner->post
            ],
            [
                'label' => 'Участники',
                'value' => implode(', ', $model->participantNameList)
            ],
            'note',
            'rsoAgreement'
        ]
    ]); ?>

    <div class="panel panel-default">

        <div class="panel-heading"><b>Прикрепленные файлы</b></div>

        <ul class="list-group">

            <?php foreach ($model->rsoFiles as $rsoFile) : ?>

                <li class="list-group-item"><?= Html::a($rsoFile['name'], ['rso/render-file', 'id' => (string)$rsoFile['id']],['target' => '_blank']) ?></li>

            <?php endforeach; ?>

        </ul>

    </div>

    <div>

        <?= Html::a('Одобрить', ['rso/approve-request', 'id' => (string)$model->_id], ['class' => 'btn btn-success', 'style' => 'display:inline-block']) ?>
        <?= Html::a('Отказать', ['rso/refuse-request', 'id' => (string)$model->_id], ['class' => 'btn btn-danger', 'style' => 'display:inline-block']) ?>

    </div>

</div>