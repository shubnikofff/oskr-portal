<?php
/**
 * teleport
 * Created: 27.11.15 15:51
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */
use kartik\helpers\Html;

/**
 * @var $this \yii\web\View
 * @var $model \common\models\User
 */
$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div>

    <?= Html::pageHeader($this->title) ?>

    <p>Уважаемый, <?= $model->fullName ?>, регистрация на портале прошла успешно. На указанный Вами адрес
        <strong><?= $model->email ?></strong> было отправлено письмо с инструкциями по активации Вашей
        учетной записи.</p>

</div>
