<?php
/**
 * teleport
 * Created: 30.11.15 13:05
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */

/**
 * @var $model \common\models\User
 */
$confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['site/confirm-signup', 'token' => $model->activateToken]);
?>

Здравствуйте!

Вы получили это письмо, потому что Ваш адрес был указан при регистрации на портале Отдела системных корпоративных ресурсов <?= Yii::$app->name ?>.

Если это были Вы, пожалуйста активируйте Вашу учетную запись (<?= $model->username ?>), пройдя по следующей ссылке:

<?= $confirmLink ?>


Если вы не совершали регистрацию на Телепорт просто проигнорируйте это письмо.
