<?php
/**
 * teleport
 * Created: 15.10.15 10:47
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */

use yii\helpers\Html;

/**
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $model \frontend\models\vks\RequestSearch
 */
$minTime = Yii::$app->params['vks.minTime'];
$maxTime = Yii::$app->params['vks.maxTime'];
?>

    <p class="lead">Расписание на <?= Yii::$app->formatter->asDate($model->date->sec, 'long') ?></p>

    <!--<table id="table1" class="v-table">
        <tr>
            <td><div class="v-item" style="top: 15px; height: 60px">
                    <div class="theme"><a href="#">Перв111ая тема Первая тема Первая тема Первая тема Первая тема Первая тема </a></div>
                    <div class="participants"><b>НН 325 - МФ ЦУП</b></div>
                    <div class="service-info">RMX</div>
                </div></td>
            <td><div class="v-item" style="top: 30px; height: 90px">
                    <div class="theme"><a href="#">Перв111ая тема Первая тема Первая тема Первая тема Первая тема Первая тема </a></div>
                    <div class="participants">НН 325 - МФ ЦУП</div>
                    <div class="service-info">RMX</div>
                </div></td>
            <td><div class="v-item" style="top: 60px; height: 60px">
                    <div class="theme"><a href="#">Перв111ая тема Первая тема Первая тема Первая тема Первая тема Первая тема </a></div>
                    <div class="participants">НН 325 - МФ ЦУП</div>
                    <div class="service-info">RMX</div>
                </div></td>
        </tr>
    </table>

    <table id="table2" class="v-table">
        <tr>
            <td><div class="v-item" style="top: 150px; height: 90px"><a href="#">Четвертая тема</a></div></td>
        </tr>
    </table>-->

    <div id="vks-schedule">

        <table class="vks-time-grid">

            <?php for ($i = $minTime; $i < $maxTime; $i += 30): ?>

                <?php if ($i % 60 == 0): ?>

                    <tr class="vks-time-grid full-hour">
                        <td><?= (string)($i / 60) ?><sup>00</sup></td>
                    </tr>

                <?php else: ?>

                    <tr class="vks-time-grid half-hour">
                        <td></td>
                    </tr>

                <?php endif; ?>

            <?php endfor; ?>

        </table>

    </div>

    <div id="vks-schedule-legend">
        <div>
            <div class="status-color-box status-considiration"></div>
            - на рассмотрении
        </div>
        <div>
            <div class="status-color-box status-approve"></div>
            - согласованные
        </div>
        <!--<div><div class="status-color-box status-ahu-approve"></div> - согласованные АХУ</div>-->
        <div>
            <div class="status-color-box status-cancel"></div>
            - отмененные
        </div>
    </div>

<?php $options = \yii\helpers\Json::encode([
    'minTime' => $minTime,
    'maxTime' => $maxTime,
    'itemsContainerSelector' => '#vks-schedule-items-container',
    'itemsSelector' => 'button.vks-item',
    'modalWidgetSelector' => '#vks-view-modal-widget',
    'modalContainerSelector' => '#vks-view-container'
]);
$this->registerJs("$('#vks-schedule-table').schedule({$options});"); ?>