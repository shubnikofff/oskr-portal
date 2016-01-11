<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
$this->title = Yii::$app->name;
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Телепорт</h1>

        <p class="lead">Панель управления</p>

    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Участники ВКС</h2>

                <p>Справочник участников ВКС позволяет управлять информацией об участниках. Группировать их по компаниям, и многое другое</p>

                <p><?= Html::a('Справочник участников &raquo;', ['vks-participant/index'], ['class' => 'btn btn-default'])?></p>

            </div>
            <div class="col-lg-4">
                <h2>Пользователи системы</h2>

                <p>Данный раздел позволяет управлять пользователями системы. Назначять им роли и привилегии, а также менять информацию профиля.</p>

                <p><?= Html::a('Пользователи сисетмы &raquo;', ['user/index'], ['class' => 'btn btn-default'])?></p>
            </div>
            <div class="col-lg-4">
                <h2>Компании</h2>

                <p>Справочник компаний содержит информацию об организациях, которые могут принимать участие в ВКС.
                    Также компании назначаются пользователям при регистрации. Данный раздел позволяет редактировать справочник компаний.</p>

                <p><?= Html::a('Справочник компаний &raquo;', ['vks-company/index'], ['class' => 'btn btn-default'])?></p>
            </div>
        </div>

    </div>
</div>
