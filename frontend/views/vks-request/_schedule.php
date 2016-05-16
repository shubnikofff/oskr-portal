<?php
/**
 * teleport
 * Created: 15.10.15 10:47
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */

use yii\helpers\Html;
use frontend\models\vks\Request;

/**
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $model \frontend\models\vks\RequestSearch
 */
$minMinute = Yii::$app->params['vks.minTime'];
$maxMinute = Yii::$app->params['vks.maxTime'];
?>
    <p class="lead">Расписание на <?= Yii::$app->formatter->asDate($model->date->sec, 'long') ?></p>

    <div id="vks-schedule">

        <?php $currentMinute = \common\components\MinuteFormatter::asInt(date('H:i')) ?>

        <?php if (gmmktime(0, 0, 0) == $model->date->sec && $currentMinute >= $minMinute && $currentMinute <= $maxMinute): ?>

            <?= Html::tag('div', '', [
                'id' => 'current-time',
                'data' => [
                    'top' => $currentMinute - $minMinute + 1
                ]
            ]) ?>

        <?php endif; ?>

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

            <?= Html::beginTag('table', ['id' => 'vks-schedule-grid']) ?>

            <tr>
                <?php $groupsCount = count($schedule) ?>

                <?php foreach ($schedule as $requestGroup): ?>

                    <td class="vks-schedule-grid" style="width: <?= 100 / $groupsCount ?>%">

                        <?php foreach ($requestGroup as $request): ?>

                            <?php /** @var \frontend\models\vks\Request $request */

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


                            <?= Html::beginTag('button', [
                                'class' => $statusClass . ' vks-request',
                                'title' => "{$request->beginTimeString} - {$request->endTimeString} ({$participantList})",
                                'data' => [
                                    'href' => \yii\helpers\Url::to(['/vks-request/view', 'id' => (string)$request->primaryKey]),
                                    'top' => $request->beginTime - $minMinute,
                                    'height' => $request->endTime - $request->beginTime - 1
                                ]
                            ]) ?>

                            <?= Html::beginTag('div', ['class' => 'vks-request-theme']) ?>

                            <?= Html::beginTag('div', ['class' => 'vks-request-options']) ?>

                            <?php if ($request->mode === $request::MODE_WITH_VKS): ?>

                                <span class="glyphicon glyphicon-facetime-video"></span>

                            <?php endif; ?>

                            <?php if ($request->audioRecord): ?>

                                <span class="glyphicon glyphicon-headphones"></span>

                            <?php endif; ?>

                            <?= Html::endTag('div') ?>

                            <?= $request->topic ?>

                            <?= Html::endTag('div') ?>

                            <?= Html::tag('div', "<b>{$participantList}</b>", ['class' => 'vks-request-participants']) ?>

                            <?= Html::tag('div', $request->deployServer ? $request->deployServer->name : "", ['class' => 'vks-request-service-data']) ?>

                            <?= Html::endTag('button') ?>

                        <?php endforeach; ?>

                    </td>

                <?php endforeach; ?>

            </tr>

            <?= Html::endTag('table') ?>

        <?php endif; ?>


        <?= Html::beginTag('table', ['class' => 'vks-time-grid']) ?>

        <?php for ($i = $minMinute; $i < $maxMinute; $i += 30): ?>

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

        <?= Html::endTag('table') ?>

    </div>

    <div id="vks-schedule-legend">
        <div>
            <div class="status-color-box status-considiration"></div>
            - <?= Request::statusName(Request::STATUS_CONSIDERATION) ?>
        </div>
        <div>
            <div class="status-color-box status-approve"></div>
            - <?= Request::statusName(Request::STATUS_APPROVE) ?>
        </div>
        <div>
            <div class="status-color-box status-ahu-approve"></div>
            - <?= Request::statusName(Request::STATUS_ROOMS_CONSIDIRATION) ?>
        </div>
        <div>
            <div class="status-color-box status-cancel"></div>
            - <?= Request::statusName(Request::STATUS_CANCEL) ?>
        </div>
    </div>

<?php $options = \yii\helpers\Json::encode([
    'timeColumnWidth' => 40,
    'timeGridSelector' => 'table.vks-time-grid',
    'currentTimeSelector' => '#current-time',
    'requestsGridSelector' => '#vks-schedule-grid',
    'requestContainerSelector' => 'button.vks-request',
    'modalWidgetSelector' => '#vks-view-modal-widget',
    'modalContentSelector' => '#vks-view-container',
]);
$this->registerJs("$('#vks-schedule').schedule({$options});"); ?>