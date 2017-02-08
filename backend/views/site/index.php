<?php
/* @var $this yii\web\View */
use yii\helpers\Html;

$this->title = Yii::$app->name;
?>
<div class="site-index">

    <div class="jumbotron">

        <h1>ОСКР Портал</h1>
        <p class="lead">Панель управления</p>

    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4 col-lg-offset-2">

                <div class="panel panel-default">

                    <div class="panel-heading">
                        <h3 class="panel-title">ВКС</h3>
                    </div>

                    <div class="panel-body">
                        <ul>
                            <li><?= Html::a('Участники', ['/vks-participant/index']) ?></li>
                        </ul>
                        <ul>
                            <li><?= Html::a('Компании', ['/vks-company/index']) ?></li>
                        </ul>
                        <ul>
                            <li><?= Html::a('MCU', ['/mcu/index']) ?></li>
                        </ul>
                        <ul>
                            <li><?= Html::a('Типы аудиозаписи', ['/audio-record-type/index']) ?></li>
                        </ul>
                        <ul>
                            <li><?= Html::a('Порядок отображения компаний', ['/order/save']) ?></li>
                        </ul>
                    </div>
                </div>

            </div>

            <div class="col-lg-4">

                <div class="panel panel-default">

                    <div class="panel-heading">
                        <h3 class="panel-title">Безопасность</h3>
                    </div>

                    <div class="panel-body">
                        <ul>
                            <li><?= Html::a('Пользователи', ['/users']) ?></li>
                        </ul>
                        <ul>
                            <li><?= Html::a('Роли', ['/roles']) ?></li>
                        </ul>
                        <ul>
                            <li><?= Html::a('Привилегии', ['/permissions']) ?></li>
                        </ul>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
