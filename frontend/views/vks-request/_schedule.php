<?php
/**
 * teleport
 * Created: 15.10.15 10:47
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use frontend\models\vks\MCURepository;
use frontend\models\vks\Schedule;

/**
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $model \frontend\models\vks\RequestSearch
 */
$minMinute = Yii::$app->params['vks.minTime'];
$maxMinute = Yii::$app->params['vks.maxTime'];
$isOskrUser = Yii::$app->user->can(\common\rbac\SystemPermission::APPROVE_REQUEST);
$mcuServers = $isOskrUser ? ArrayHelper::map(MCURepository::instance()->getRaw(), 'id', 'name') : [];
$participantsCountPerHour = $isOskrUser ? (new Schedule($model->date))->participantsCountPerHour() : [];
?>
    <p class="lead" style="padding-top: 90px">Расписание
        на <?= Yii::$app->formatter->asDate($model->date->toDateTime(), 'long') ?></p>

    <div id="vks-schedule">

        <?php $currentMinute = \common\components\MinuteFormatter::asInt(date('H:i')) ?>

        <?php if (gmmktime(0, 0, 0) == $model->date->toDateTime()->getTimestamp() && $currentMinute >= $minMinute && $currentMinute <= $maxMinute): ?>

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
                                case $request::STATUS_CANCELED:
                                    $statusClass = 'status-cancel';
                                    break;
                                case $request::STATUS_APPROVED:
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

                            <?php $mcuName = ($isOskrUser && $request->mode === $request::MODE_WITH_VKS) ?
                                $mcuName = $request->conference ? $mcuServers[$request->conference->getMcuId()] : 'Конференция не создана' : '' ?>

                            <?= Html::beginTag('button', [
                                'class' => $statusClass . ' vks-request',
                                'title' => "№{$request->number} {$request->beginTimeString} - {$request->endTimeString} {$mcuName} ({$participantList}) ",
                                'data' => [
                                    'href' => \yii\helpers\Url::to(['/vks-request/view', 'id' => (string)$request->primaryKey]),
                                    'top' => $request->beginTime - $minMinute,
                                    'height' => $request->endTime - $request->beginTime - 1
                                ]
                            ]) ?>

                            <div class="vks-request-header">

                                <div class="vks-request-number"><strong>№ <?= $request->number ?></strong></div>

                                <div class="vks-request-options">

                                    <?php if ($request->note !== ''): ?>

                                        <span class="glyphicon glyphicon-warning-sign"></span>

                                    <?php endif; ?>

                                    <?php if ($request->mode === $request::MODE_WITH_VKS): ?>

                                        <span class="glyphicon glyphicon-facetime-video"></span>

                                    <?php endif; ?>

                                    <?php if ($request->isVim === '1'): ?>

                                        <span style="color: red" class="glyphicon glyphicon-star"></span>

                                    <?php endif; ?>

                                </div>

                            </div>

                            <?= Html::tag('div', $request->topic, ['class' => 'vks-request-theme']) ?>

                            <div class="vks-request-service-data"><strong><?= $mcuName ?></strong></div>

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

            echo Html::beginTag('tr', ['class' => 'vks-time-grid ' . ($isFullHour ? 'full-hour' : 'half-hour')]);
            echo Html::tag('td', $isFullHour ? (string)($i / 60) . Html::tag('sup', '00') : '', ['width' => 1]);

            if ($isOskrUser) {
                $participantsCount = $participantsCountPerHour[$i];
                $participantsCountColorClass = 'participants-count-success';
                if ($participantsCount >= 25) {
                    $participantsCountColorClass = 'participants-count-warning';
                }
                if ($participantsCount >= 33) {
                    $participantsCountColorClass = 'participants-count-danger';
                }
                echo Html::tag('td', Html::tag('b', $participantsCount), ['class' => 'participants-count small ' . $participantsCountColorClass]);
            }

            echo Html::endTag('tr');
        } ?>

        <?= Html::endTag('table') ?>

    </div>

    <div id="vks-schedule-legend">

        <div class="row">

            <div class="col-lg-3">
                <div class="status-color-box status-approve"></div>
                согласовано
            </div>

            <div class="col-lg-3">
                <div class="status-color-box status-considiration"></div>
                на рассмотрении УСКР
            </div>

            <div class="col-lg-3">
                <div class="status-color-box status-ahu-approve"></div>
                комнаты на рассмотрении
            </div>

            <div class="col-lg-3">
                <div class="status-color-box status-cancel"></div>
                отменено
            </div>

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
    'getProfilesURL' => \yii\helpers\Url::to(['/conference/get-profiles'])
]);
$this->registerJs("$('#vks-schedule').schedule({$options});"); ?>