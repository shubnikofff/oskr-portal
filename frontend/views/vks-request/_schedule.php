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
 * @var $modalWidgetSelector string
 * @var $modalContentSelector string
 */
$minTime = Yii::$app->params['vks.minTime'];
$maxTime = Yii::$app->params['vks.maxTime'];
?>
    <p class="lead">Расписание на <?= Yii::$app->formatter->asDate($model->date->sec, 'long') ?></p>

    <div id="vks-schedule">

        <?php if ($dataProvider->totalCount): ?>

            <?php /** @var \frontend\models\vks\Request[] $requests */
            $requests = $dataProvider->getModels();
            $groupedRequests[] = [$requests[0]];

            for ($i = 1; $i < count($requests); $i++) {
                if ($requests[$i]->beginTime < $requests[$i - 1]->endTime) {
                    $groupedRequests[count($groupedRequests) - 1][] = $requests[$i];
                } else {
                    $groupedRequests[] = [$requests[$i]];
                }
            } ?>

            <?php foreach ($groupedRequests as $requestsGroup) : ?>

                <table class="vks-request-grid">

                    <tr>

                        <?php foreach ($requestsGroup as $request): ?>
                            <?php /** @var $request \frontend\models\vks\Request */ ?>

                            <td class="vks-request-grid">

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

                                <div class="vks-request <?= $statusClass ?>"
                                     style="top: <?= $top ?>px; height: <?= $height ?>px"
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

                            </td>

                        <?php endforeach; ?>

                    </tr>

                </table>

            <?php endforeach; ?>

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
    'requestsGridSelector' => 'table.vks-request-grid',
    'modalWidgetSelector' => $modalWidgetSelector,
    'modalContentSelector' => $modalContentSelector,
    'requestReferenceSelector' => 'a.vks-request-theme'
]);
$this->registerJs("$('#vks-schedule').schedule({$options});"); ?>