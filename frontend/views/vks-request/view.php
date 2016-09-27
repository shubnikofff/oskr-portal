<?php
/**
 * teleport
 * Created: 28.10.15 13:41
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */
use yii\bootstrap\Html;
use common\rbac\SystemPermission;
use common\models\vks\Participant;

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

    <p style="font-size: 20px;"><?= $model->topic ?></p>

    <hr>

    <div>

        <div style="font-size: 15px; display: inline-block">
            Время проведения <?= Yii::$app->formatter->asDate($model->date->sec, 'long') ?>
            c <?= $model->beginTimeString ?> до <?= $model->endTimeString ?>
        </div>

        <div class="pull-right" style="display: inline-block">

            <?php if ($model->status == \frontend\models\vks\Request::STATUS_APPROVE): ?>

                <?= Html::a("<span class='glyphicon glyphicon-print'></span> Распечатать", ['vks-request/print', 'id' => (string)$model->primaryKey], [
                    'class' => 'btn btn-default',
                    'target' => '_blank'
                ]) ?>

            <?php endif; ?>

        </div>

    </div>

    <?php switch ($model->status) {
        case $model::STATUS_APPROVE:
            $statusCssClass = 'text-success';
            break;
        case $model::STATUS_CANCEL:
            $statusCssClass = 'text-danger';
            break;
    } ?>

    <p>Статус заявки: <b class="<?= $statusCssClass ?>"><?= $model->statusName ?></b></p>

    <?php if ($model->status === $model::STATUS_CANCEL): ?>

        <p class="text-danger" style="font-size: large"><?= $model->cancellationReason ?></p>

    <?php endif; ?>

    <p>Согласование с РСО: <b><?= $model->rsoAgreement ?></b></p>

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

    <p>Совещание в режиме ВКС: <b><?= Yii::$app->formatter->asBoolean($model->mode === $model::MODE_WITH_VKS) ?></b></p>

    <?php if ($model->mode === $model::MODE_WITH_VKS): ?>

        <?php if ($model->audioRecord): ?>

            <p class="text-danger" style="font-size: 11pt"><strong>Необходима аудиозапись ВКС</strong></p>

        <?php endif; ?>

        <?php if ($isOSKRUser): ?>

            <?= $this->render('_deployServerForm', ['model' => $model]) ?>

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
                <?php if ($isOSKRUser): ?>
                    <td><?= $participant->ipAddress ?></td>
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

        <?php if ($model->status !== $model::STATUS_APPROVE && $isOSKRUser): ?>

            <?= Html::a("<span class='glyphicon glyphicon-ok'></span> Согласовать", ['vks-request/approve', 'id' => (string)$model->primaryKey], [
                'class' => 'btn btn-success',
                'data' => ['method' => 'post']
            ]) ?>

        <?php endif; ?>

        <?php if ($model->status !== $model::STATUS_CANCEL && Yii::$app->user->can(SystemPermission::CANCEL_REQUEST, ['object' => $model])): ?>

            <?= Html::a("<span class='glyphicon glyphicon-ban-circle'></span> Отменить", ['vks-request/cancel', 'id' => (string)$model->primaryKey], ['class' => 'btn btn-warning']) ?>

        <?php endif; ?>

        <?php if (Yii::$app->user->can(SystemPermission::DELETE_REQUEST)): ?>

            <?= Html::a("<span class='glyphicon glyphicon-trash'></span> Удалить", ['vks-request/delete', 'id' => (string)$model->primaryKey], [
                'class' => 'btn btn-danger',
                'data' => ['method' => 'post', 'confirm' => 'Удалить данную заявку ?']
            ]) ?>

        <?php endif; ?>

    </div>

    <div style="margin-bottom: 12px">

        <?php if ($isUserCanRsoAgree): ?>

            <?= Html::a('Одобрить', ['rso/approve-request', 'id' => (string)$model->_id], ['class' => 'btn btn-success', 'style' => 'display:inline-block']) ?>

        <?php endif; ?>

        <?php if ($isUserCanRsoRefuse): ?>

            <?= Html::a('Отказать', ['rso/refuse-request', 'id' => (string)$model->_id], ['class' => 'btn btn-danger', 'style' => 'display:inline-block']) ?>

        <?php endif; ?>

    </div>

    <p>Заявка подана <?= Yii::$app->formatter->asDatetime($model->createdAt->sec, 'd MMMM y г. HH:mm') ?></p>

</div>

<?php $this->registerJs('$(\'[data-toggle="popover"]\').popover({html: true});') ?>
