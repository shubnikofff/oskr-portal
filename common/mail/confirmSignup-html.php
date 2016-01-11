<?php
/**
 * teleport
 * Created: 30.11.15 10:31
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */
use yii\helpers\Html;

/**
 * @var $model \common\models\User
 */
$confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['site/confirm-signup', 'token' => $model->activateToken]);
?>

<p>Здравствуйте!</p>

<p>Вы получили это письмо, потому что Ваш адрес был указан при регистрации на портале
    Отдела системных корпоративных ресурсов <strong><?= Yii::$app->name ?></strong>.</p>

<p>Если это были Вы, пожалуйста активируйте Вашу учетную запись <strong><?= $model->username ?></strong>, пройдя по следующей
    ссылке:</p>

<p><?= Html::a(Html::encode($confirmLink), $confirmLink) ?></p>

<p>Если вы не совершали регистрацию на <strong>Телепорт</strong> просто проигнорируйте это письмо.</p>
