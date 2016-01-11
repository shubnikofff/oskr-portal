<?php
/**
 * teleport
 * Created: 17.12.15 17:21
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */
use yii\helpers\Html;
/**
 * @var $model \frontend\models\vks\Request
 */
$viewLink = Yii::$app->urlManager->createAbsoluteUrl(['vks-request/view', 'id' => (string)$model->primaryKey]);
?>
<p>Здравствуйте!</p>

<p>Вы подавали заявку на совещание в режиме ВКС по теме:</p>

<p style="font-size: large"><i>"<?= $model->topic ?>"</i></p>

<p>Статус заявки изменился на <strong><?= $model->statusName ?></strong></p>

<?php if($model->status === $model::STATUS_CANCEL):?>
    <p><strong>Причина отмены:</strong> <?= $model->cancellationReason ?></p>
<?php endif; ?>

<p>Более подробную информацию о заявке Вы можете получить пройдя по следующей ссылке:</p>

<p><?= Html::a(Html::encode($viewLink), $viewLink) ?></p>
