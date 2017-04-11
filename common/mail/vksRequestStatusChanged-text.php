<?php
/**
 * teleport
 * Created: 17.12.15 17:22
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */
/**
 * @var $model \frontend\models\vks\Request
 */
$viewLink = Yii::$app->urlManager->createAbsoluteUrl(['vks-request/view', 'id' => (string)$model->primaryKey]);
?>

Здравствуйте!

Вы подавали заявку на совещание в режиме ВКС по теме:

"<?= $model->topic ?>"

Статус заявки изменился на "<?= $model->statusName ?>"

<?php if($model->status === $model::STATUS_CANCELED):?>
Причина отмены: <?= $model->cancellationReason ?>
<?php endif; ?>

Более подробную информацию о заявке Вы можете получить пройдя по следующей ссылке:

<?= $viewLink ?>
