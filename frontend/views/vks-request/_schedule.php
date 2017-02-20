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
 * @var $participantsCountPerHour array
 */
$minMinute = Yii::$app->params['vks.minTime'];
$maxMinute = Yii::$app->params['vks.maxTime'];
?>
    <p class="lead">Расписание на <?= Yii::$app->formatter->asDate($model->date->toDateTime(), 'long') ?></p>

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
                                case $request::STATUS_OSKR_CONSIDERATION:
                                    $statusClass = 'status-considiration';
                                    break;
                                case $request::STATUS_ROOMS_CONSIDIRATION:
                                    $statusClass = 'status-ahu-approve';
                                    break;
                            } ?>

                            <?php $participantList = implode(' - ', $request->participantShortNameList) ?>

                            <?= Html::beginTag('button', [
                                'class' => $statusClass . ' vks-request',
                                'title' => "№{$request->number} {$request->beginTimeString} - {$request->endTimeString} ({$participantList})",
                                'data' => [
                                    'href' => \yii\helpers\Url::to(['/vks-request/view', 'id' => (string)$request->primaryKey]),
                                    'top' => $request->beginTime - $minMinute,
                                    'height' => $request->endTime - $request->beginTime - 1
                                ]
                            ]) ?>

                            <div class="vks-request-header">

                                <div class="vks-request-number"><strong>№ <?= $request->number ?></strong></div>

                                <div class="vks-request-options">

                                    <?php if ($request->mode === $request::MODE_WITH_VKS): ?>

                                        <span class="glyphicon glyphicon-facetime-video"></span>

                                    <?php endif; ?>

                                </div>

                            </div>

                            <?= Html::tag('div', $request->topic, ['class' => 'vks-request-theme']) ?>

                            <div class="vks-request-service-data"><strong><?= $request->mcu->name ?></strong></div>

                            <?= Html::endTag('button') ?>

                        <?php endforeach; ?>

                    </td>

                <?php endforeach; ?>

            </tr>

            <?= Html::endTag('table') ?>

        <?php endif; ?>


        <?= Html::beginTag('table', ['class' => 'vks-time-grid']) ?>

        <?php for ($i = $minMinute; $i < $maxMinute; $i += 30) {

            $isFullHour = !(bool)($i % 60);
            $participantsCount = $participantsCountPerHour[$i];
            $participantsCountColorClass = 'participants-count-success';
            if ($participantsCount >= 25) {
                $participantsCountColorClass = 'participants-count-warning';
            }
            if ($participantsCount >= 33) {
                $participantsCountColorClass = 'participants-count-danger';
            }

            echo Html::beginTag('tr', ['class' => 'vks-time-grid ' . ($isFullHour ? 'full-hour' : 'half-hour')]);
            echo Html::tag('td', $isFullHour ? (string)($i / 60) . Html::tag('sup', '00') : '', ['width' => 1]);
            echo Html::tag('td', Html::tag('b', $participantsCount), ['class' => 'participants-count small ' . $participantsCountColorClass]);
            echo Html::endTag('tr');
        } ?>

        <?= Html::endTag('table') ?>

    </div>

    <div id="vks-schedule-legend">
        <div>
            <div class="status-color-box status-considiration"></div>
            - <?= Request::statusName(Request::STATUS_OSKR_CONSIDERATION) ?>
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
    'timeColumnWidth' => 45,
    'timeGridSelector' => 'table.vks-time-grid',
    'currentTimeSelector' => '#current-time',
    'requestsGridSelector' => '#vks-schedule-grid',
    'requestContainerSelector' => 'button.vks-request',
    'modalWidgetSelector' => '#vks-view-modal-widget',
    'modalContentSelector' => '#vks-view-container',
]);
$this->registerJs("$('#vks-schedule').schedule({$options});"); ?>