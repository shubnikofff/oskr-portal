<?php
/**
 * teleport
 * Created: 28.10.15 13:41
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */
use yii\bootstrap\Html;
use common\rbac\SystemPermission;
use common\models\vks\Participant;
use frontend\models\vks\ConferenceService;

/**
 * @var $this \yii\web\View
 * @var $model \frontend\models\vks\Request
 */
$this->title = "Заявка на ВКС";
$this->params['breadcrumbs'][] = ['label' => 'Заявки', 'url' => \yii\helpers\Url::to(['user/requests'])];
$this->params['breadcrumbs'][] = $this->title;
$isOSKRUser = Yii::$app->user->can(SystemPermission::APPROVE_REQUEST);
$isUserCanUpdateRequest = Yii::$app->user->can(SystemPermission::UPDATE_REQUEST, ['object' => $model]);
$isUserCanRsoAgree = Yii::$app->user->can(SystemPermission::RSO_AGREE);
$isUserCanRsoRefuse = Yii::$app->user->can(SystemPermission::RSO_REFUSE);
?>

    <div style="font-size: 13px">

        <div>

            <h3 style="display: inline-block">Заявка №<?= $model->number ?></h3>

            <div class="pull-right" style="display: inline-block">

                <?php if ($model->status == \frontend\models\vks\Request::STATUS_APPROVED): ?>

                    <?= Html::a("<span class='glyphicon glyphicon-print'></span> Распечатать", ['vks-request/print', 'id' => (string)$model->primaryKey], [
                        'class' => 'btn btn-default',
                        'target' => '_blank'
                    ]) ?>

                <?php endif; ?>

            </div>

        </div>

        <p style="font-size: 12pt">Время
            проведения: <?= Yii::$app->formatter->asDate($model->date->toDateTime(), 'long') ?>
            c <?= $model->beginTimeString ?> до <?= $model->endTimeString ?></p>

        <p><strong>Тема совещания:</strong> <?= $model->topic ?></p>

        <?php switch ($model->status) {
            case $model::STATUS_APPROVED:
                $statusCssClass = 'text-success';
                break;
            case $model::STATUS_CANCELED:
                $statusCssClass = 'text-danger';
                break;
        } ?>

        <p><strong>Статус заявки:</strong> <span class="<?= $statusCssClass ?>"><?= $model->statusName ?></span></p>

        <?php if ($model->status === $model::STATUS_CANCELED): ?>

            <p class="text-danger" style="font-size: large"><?= $model->cancellationReason ?></p>

        <?php endif; ?>

        <p><strong>Согласование с РСО:</strong> <?= $model->rsoAgreement ?></p>

        <?php if ($model->mode === $model::MODE_WITH_VKS && $isUserCanUpdateRequest): ?>

            <p>
                <strong>Ссылка на аудиозапись:</strong>

                <?php if ((time() + 3 * 60 * 60) > ($model->date->toDateTime()->getTimestamp() + $model->endTime * 60)) {
                    $conferenceName = ConferenceService::instance()->generateConferenceName($model);
                    echo Html::a($conferenceName . '.wav', 'http://oskrportal/records/other/' . $conferenceName . '-in.wav', ['target' => '_blank']);
                } else {
                    echo "-";
                } ?>
            </p>

        <?php endif; ?>

        <p class="lead">Организатор</p>

        <dl class="dl-horizontal">
            <dt>Имя</dt>
            <dd><?= $model->owner->fullName ?></dd>
            <dt>Должность</dt>
            <dd><?= $model->owner->post ?></dd>
            <dt>Email</dt>
            <dd><?= $model->owner->email ?></dd>
            <dt>Контактный телефон</dt>
            <dd><?= $model->owner->phone ?></dd>
        </dl>

        <p><strong>Совещание в режиме
                ВКС:</strong> <?= Yii::$app->formatter->asBoolean($model->mode === $model::MODE_WITH_VKS) ?></p>

        <?php if ($model->mode === $model::MODE_WITH_VKS): ?>

            <?php if ($isUserCanUpdateRequest): ?>

                <?= $this->render('_connectingInfo', ['model' => $model]) ?>

            <?php endif; ?>

            <?php if ($isOSKRUser): ?>

                <?= $this->render('_uskrControlPanel', ['model' => $model]) ?>

            <?php endif; ?>

        <?php endif; ?>

        <?php if ($model->mode === $model::MODE_WITHOUT_VKS && is_array($model->equipment)): ?>

            <p><b>Дополнительное оборудование:</b> <?= implode(', ', $model->equipment) ?></p>

        <?php endif; ?>

        <p class="lead">Участники</p>

        <table class="table table-condensed">

            <tr>
                <th>#</th>
                <th>Название</th>
                <th>Организация</th>
                <th>Контактное лицо</th>
                <th>Контактный телефон</th>
                <th>Согласующее лицо</th>
                <?php if ($isOSKRUser): ?>
                    <th>IP адрес</th>
                <?php endif; ?>
                <th>Статус</th>

            </tr>
            <?php $counter = 1 ?>

            <tbody>

            <?php foreach ($model->participants as $participant): ?>

                <?php switch ($model->getRoomStatus($participant->_id)) {
                    case Participant::STATUS_CONSIDIRATION:
                        $roomStatus = "<span title='На рассмотрении' class='text-warning glyphicon glyphicon-question-sign'></span>";
                        break;
                    case Participant::STATUS_CANCEL:
                        $roomStatus = "<span title='Отменено' class='text-danger glyphicon glyphicon-remove'></span>";
                        break;
                    default:
                        $roomStatus = "<span title='Согласовано' class='text-success glyphicon glyphicon-ok'></span>";
                        break;
                } ?>

                <tr>
                    <td><?= $counter ?></td>
                    <td><?= $participant->name ?></td>
                    <td><?= $participant->company->name ?></td>
                    <td><?= $participant->contact ?></td>
                    <td><?= $participant->phone ?></td>
                    <td><?= ($confirmPerson = $participant->confirmPerson) ? $confirmPerson->fullName . ' тел.: ' . $confirmPerson->phone . ' ' .
                            Html::a($confirmPerson->email, 'mailto:' . $confirmPerson->email) : '' ?></td>
                    <?php if ($isOSKRUser): ?>
                        <td><?= $participant->dialString ?></td>
                    <?php endif; ?>
                    <td style="text-align: center"><?= $roomStatus ?></td>
                </tr>
                <?php $counter++ ?>
            <?php endforeach; ?>

            </tbody>

        </table>

        <?php if ($model->note): ?>

            <p><b style="font-size: 14px">Примечание</b><p><?= $model->note ?></p>

        <?php endif; ?>

        <?php if ($isUserCanUpdateRequest || $isUserCanRsoAgree): ?>

            <div class="panel panel-default">

                <div class="panel-heading"><b>Прикрепленные файлы для РСО</b></div>

                <ul class="list-group">

                    <?php foreach ($model->rsoFiles as $rsoFile) : ?>

                        <li class="list-group-item"><?= Html::a($rsoFile['name'], ['vks-request/render-file', 'id' => (string)$rsoFile['id']], ['target' => '_blank']) ?></li>

                    <?php endforeach; ?>

                </ul>

            </div>

        <?php endif; ?>

        <?php if (is_array($model->log)): ?>

            <?= $this->render('_requestLog', ['model' => $model]) ?>

        <?php endif; ?>

        <div style="margin-bottom: 12px">

            <?php if ($isUserCanUpdateRequest): ?>

                <?= Html::a("<span class='glyphicon glyphicon-pencil'></span> Редактировать", ['vks-request/update', 'id' => (string)$model->primaryKey], ['class' => 'btn btn-primary']) ?>

            <?php endif; ?>

            <?php if ($isOSKRUser && $model->status !== $model::STATUS_APPROVED): ?>

                <?= Html::a("<span class='glyphicon glyphicon-ok'></span> Согласовать", ['vks-request/approve', 'requestId' => (string)$model->primaryKey], ['class' => 'btn btn-success', 'data' => ['method' => 'post']]) ?>

            <?php endif; ?>


            <?php if ($model->status !== $model::STATUS_CANCELED && Yii::$app->user->can(SystemPermission::CANCEL_REQUEST, ['object' => $model])): ?>

                <?= Html::a("<span class='glyphicon glyphicon-ban-circle'></span> Отменить", ['vks-request/cancel', 'id' => (string)$model->primaryKey], ['class' => 'btn btn-warning']) ?>

            <?php endif; ?>

            <?php if (Yii::$app->user->can(SystemPermission::DELETE_REQUEST)): ?>

                <?= Html::a("<span class='glyphicon glyphicon-trash'></span> Удалить", ['vks-request/delete', 'id' => (string)$model->primaryKey], [
                    'class' => 'btn btn-danger',
                    'data' => ['method' => 'post', 'confirm' => 'Удалить данную заявку ?']
                ]) ?>

            <?php endif; ?>

            <?php if ($isUserCanRsoAgree): ?>

                <?= Html::a('Одобрить', ['rso/approve-request', 'id' => (string)$model->_id], ['class' => 'btn btn-success', 'style' => 'display:inline-block']) ?>

            <?php endif; ?>

            <?php if ($isUserCanRsoRefuse): ?>

                <?= Html::a('Отказать', ['rso/refuse-request', 'id' => (string)$model->_id], ['class' => 'btn btn-danger', 'style' => 'display:inline-block']) ?>

            <?php endif; ?>

        </div>

    </div>

<?php $this->registerJs('$(\'[data-toggle="popover"]\').popover({html: true});') ?>