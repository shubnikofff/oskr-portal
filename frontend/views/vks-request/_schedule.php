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

    <div id="vks-schedule">

        <?php if ($dataProvider->totalCount): ?>

            <?php function groupRequests(&$schedule, $requests)
            {
                /** @var \frontend\models\vks\Request[] $requests */
                foreach ($requests as $key => $request) {
                    $currentGroup = end($schedule);
                    if (end($currentGroup)->endTime <= $request->beginTime) {
                        $schedule[count($schedule) - 1][] = $request;
                        unset($requests[$key]);
                    }
                }

                if (count($requests)) {
                    $schedule[][] = array_shift($requests);
                    groupRequests($schedule, $requests);
                }
            }

            $requests = $dataProvider->getModels();
            $schedule[][] = array_shift($requests);
            groupRequests($schedule, $requests); ?>

            <table id="vks-schedule-grid">

                <tr>
                    <?php $groupsCount = count($schedule) ?>

                    <?php foreach ($schedule as $requestGroup): ?>

                        <td class="vks-schedule-grid" style="width: <?= 100/$groupsCount ?>%">

                            <?php foreach ($requestGroup as $request): ?>

                                <?php $top = $request->beginTime - $minTime;
                                $height = $request->endTime - $request->beginTime;
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
                                } ?>

                                <?php $participantList = implode(' - ', $request->participantShortNameList) ?>

                                <div class="vks-request <?= $statusClass ?>" style="top: <?= $top ?>px; height: <?= $height ?>px"
                                     title="<?= $request->beginTimeString ?> - <?= $request->endTimeString ?> (<?= $participantList ?>)">

                                    <div class="vks-request-theme">
                                        <?= Html::a($request->topic, ['/vks-request/view', 'id' => (string)$request->primaryKey], ['class' => 'vks-request-theme',]) ?>
                                    </div>
                                    <div class="vks-request-participants">
                                        <b><?= $participantList ?></b></div>
                                    <div class="vks-request-service-data">
                                        <small>
                                            <?= ($request->deployServer) ? $request->deployServer->name : "" ?>&nbsp;
                                            <?php if ($request->audioRecord): ?>
                                                <span class="glyphicon glyphicon-headphones"></span>
                                            <?php endif; ?>
                                        </small>
                                    </div>

                                </div>

                            <?php endforeach; ?>

                        </td>

                    <?php endforeach; ?>

                </tr>

            </table>

        <?php endif; ?>

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
    'timeColumnWidth' => 40,
    'timeGridSelector' => 'table.vks-time-grid',
    'requestsGridSelector' => '#vks-schedule-grid',
    'requestContainerSelector' => 'div.vks-request',
    'modalWidgetSelector' => '#vks-view-modal-widget',
    'modalContentSelector' => '#vks-view-container',
    'requestReferenceSelector' => 'a.vks-request-theme'
]);
$this->registerJs("$('#vks-schedule').schedule({$options});"); ?>