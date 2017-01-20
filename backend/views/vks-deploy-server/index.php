<?php

use yii\grid\GridView;
use kartik\helpers\Html;
use yii\grid\ActionColumn;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Сервера сборки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deploy-server-index">

    <?= Html::pageHeader($this->title) ?>

    <p>
        <?= Html::a("<span class='glyphicon glyphicon-plus'></span>", ['create'], ['class' => 'btn btn-default']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => ActionColumn::className(),
                'template' => '{update}',
                'contentOptions' => ['width' => '1px;']
            ],

            'name',
            'ip',
            'brand',

            [
                'class' => ActionColumn::className(),
                'template' => '{delete}',
                'contentOptions' => ['width' => '1px;']
            ],
        ],
    ]); ?>

</div>
