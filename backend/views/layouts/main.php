<?php

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use common\widgets\Alert;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrap">

    <?php
    if (!Yii::$app->user->isGuest) {

        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);

        echo Nav::widget([
            'activateParents' => true,
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [

                ['label' => 'Бронирование помещений', 'items' => [
                    ['label' => 'Участники', 'url' => ['/vks-participant/index']],
                    ['label' => 'Группы помещений', 'url' => ['/room-group/index']],
                    ['label' => 'Сервера сборки', 'url' => ['/vks-deploy-server/index']],
                    ['label' => 'Порядок отображения', 'url' => ['/order/save']],
                ]],

                ['label' => 'Безопасность', 'items' => [
                        'user' => ['label' => 'Пользователи', 'url' => ['/user/index']],
                        'role' => ['label' => 'Роли', 'url' => ['/role/index']],
                        'permission' => ['label' => 'Привилегии', 'url' => ['/permission/index']],
                ]],

                [
                    'label' => 'Выход (' . Yii::$app->user->identity['shortName'] . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ]
            ],
        ]);
        NavBar::end();
    }
    ?>


    <div class="container">
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>

</div>

<footer class="footer">
    <div class="container">
        <p class="text-center">&copy;&nbsp;<?= date('Y') ?> ОСКР АО"НИАЭП"</p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
