<?php
/**
 * oskr.local
 * Created: 21.06.16 15:18
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */
use yii\helpers\Html;

/**
 * @var $this \yii\web\View
 * @var $model \frontend\models\vks\Request
 */
?>

<div class="panel panel-default" id="log">

    <div class="panel-heading" id="log-header">

        <h4 class="panel-title">
            <a href="#log-body" data-toggle="collapse">История изменений в заявке</a>
        </h4>

    </div>

    <div id="log-body" class="panel-collapse collapse">

        <table class="table">

            <?php foreach ($model->log as $item): ?>

                <tr>

                    <?= Html::tag('td', Yii::$app->formatter->asDatetime($item['date']->toDateTime())) ?>
                    <?= Html::tag('td', nl2br($item['action'])) ?>
                    <?= Html::tag('td', $item['user']) ?>

                </tr>

            <?php endforeach; ?>

        </table>

    </div>

</div>
