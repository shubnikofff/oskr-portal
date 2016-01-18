<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->passwordResetToken]);
?>
Здравствуйте, <?= $user->fullName ?>.

Для сброса пароля проследуйте по следующей ссылке:

<?= $resetLink ?>
