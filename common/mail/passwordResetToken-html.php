<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->passwordResetToken]);
?>
<div class="password-reset">

    <p>Здравствуйте, <?= Html::encode($user->fullName) ?>.</p>

    <p>Вы зарегистрированы на <?= Html::encode(Yii::$app->name) ?> с именем(логином)
        <b><?= Html::encode($user->username) ?></b></p>

    <p>Для сброса пароля проследуйте по следующей ссылке:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>

</div>