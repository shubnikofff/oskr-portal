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
?>

<?php $timeLine = [
    'start' => Yii::$app->params['vks.minTime'],
    'length' => Yii::$app->params['vks.maxTime'] - Yii::$app->params['vks.minTime']
];

$printTimeLine = function () use ($timeLine) {
    $i = $timeLine['start'] / 60;
    while ($i < ($timeLine['start'] + $timeLine['length']) / 60) {
        echo Html::tag('td', "{$i}:00");
        $i++;
    }
} ?>

    <p class="lead">Расписание на <?= Yii::$app->formatter->asDate($model->date->sec, 'long') ?></p>

    <table id="vks-schedule-table">

        <tr id="vks-schedule-header">

            <?php $printTimeLine(); ?>

        </tr>

        <?php $requests = $dataProvider->getModels(); ?>

        <?php if (count($requests)): ?>

            <?php foreach ($requests as $request): ?>

                <?php /** @var $request \frontend\models\vks\Request */

                $statusClass = '';
                switch ($request->status) {
                    case $request::STATUS_CANCEL:
                        $statusClass = 'status-cancel';
                        break;
                    case $request::STATUS_APPROVE:
                        $statusClass = 'status-approve';
                        break;
                    case $request::STATUS_CONSIDERATION:
                        $statusClass = 'status-considiration';
                        break;
                }

                $audioRecord = ($request->audioRecord) ? "<span class='glyphicon glyphicon-headphones'></span>" : "";
                $deployServer = ($request->deployServerId) ? $request->deployServer->name : "";

                $itemContent = "<small><div>$request->topic</div>" . implode(' ', [$audioRecord, $deployServer]) .
                    "<div class='participants-names'><b>" . implode(' - ', $request->participantShortNameList) . "</b></div></small>" ?>
                <tr>
                    <td colspan="10"><?= Html::button($itemContent, [
                            'class' => "vks-item {$statusClass}",
                            'title' => "Время проведения c {$request->beginTimeString} по {$request->endTimeString}",
                            'data' => [
                                'url' => \yii\helpers\Url::toRoute(['vks-request/view', 'id' => (string)$request->primaryKey]),
                                'beginTime' => $request->beginTime,
                                'endTime' => $request->endTime
                            ]
                        ]) ?>
                    </td>
                </tr>

            <?php endforeach; ?>

        <?php else: ?>

            <tr>
                <td colspan="10" class="vks-not-found"><h4>Ничего не найдено</h4></td>
            </tr>


        <?php endif; ?>

        <tr id="vks-schedule-footer">

            <?php $printTimeLine(); ?>

        </tr>

    </table>
    <hr>

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
    'timeLine' => $timeLine,
    'itemsSelector' => 'button.vks-item',
    'modalWidgetSelector' => '#vks-view-modal-widget',
    'modalContainerSelector' => '#vks-view-container'
]);
$this->registerJs("$('#vks-schedule-table').schedule({$options});"); ?>