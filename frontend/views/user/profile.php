<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 07.10.15
 * Time: 10:19
 * @var $this \yii\web\View
 * @var $model \common\models\User
 */

use yii\helpers\Html;

$this->title = "Профиль";
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-view row">

    <div class="col-lg-6">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Учетные данные</h3>
            </div>
            <div class="panel-body">

                <dl>
                    <dt>Имя пользователя</dt>
                    <dd><?= $model->username ?></dd>
                </dl>

                <dl>
                    <dt>Email</dt>
                    <dd><?= $model->email ?></dd>
                </dl>

                <p>
                    <?= Html::a('Изменить пароль', ['user/update-password'], ['class' => 'btn btn-sm btn-primary']) ?>

                    <?= Html::a('Изменить Email', ['user/update-email'], ['class' => 'btn btn-sm btn-primary']) ?>
                </p>

            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Данные профиля</h3>
            </div>
            <div class="panel-body">

                <dl>
                    <dt>Имя</dt>
                    <dd><?= $model->fullName ?></dd>
                </dl>

                <dl>
                    <dt>Подразделение</dt>
                    <dd><?= $model->division ?></dd>
                </dl>

                <dl>
                    <dt>Должность</dt>
                    <dd><?= $model->post ?></dd>
                </dl>

                <dl>
                    <dt>Контактный телефон</dt>
                    <dd><?= $model->phone ?></dd>
                </dl>

                <?php if ($model->mobile): ?>
                <dl>
                    <dt>Мобильный</dt>
                    <dd><?= $model->mobile ?></dd>
                </dl>
                <?php endif; ?>

                <?= Html::a('Изменить данные профиля', ['user/update-profile'],['class' => 'btn btn-sm btn-primary']) ?>

            </div>

        </div>

    </div>

</div>
