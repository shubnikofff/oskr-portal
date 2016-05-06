<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->passwordResetToken]);
?>
Здравствуйте, <?= $user->fullName ?>.

Вы зарегистрированы на '<?= Yii::$app->name ?>' с именем(логином) '<?= $user->username ?>'

Для сброса пароля проследуйте по следующей ссылке:

<?= $resetLink ?>
