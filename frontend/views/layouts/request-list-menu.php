<?php
/**
 * oskr-portal
 * Created: 27.09.16 12:44
 * @copyright Copyright (c) 2016 OSKR NIAEP
 *
 * @var $content string
 * @var $this \yii\web\View
 */
use common\rbac\SystemPermission;
$this->beginContent('@app/views/layouts/main.php');

echo \yii\bootstrap\Nav::widget([
    'items' => [
        [
            'label' => 'Мои заявки',
            'url' => ['/user/requests']
        ],
        [
            'label' => 'Согласование брони помещений',
            'url' => ['/user/booking-approve-list'],
            'visible' => Yii::$app->user->identity->isRoomApprovePerson()
        ],
        [
            'label' => 'Сотрудникам РСО',
            'url' => ['/rso/list-requests'],
            'visible' => Yii::$app->user->can(SystemPermission::RSO_AGREE)
        ]
    ],
    'options' => ['class' =>'nav-tabs'],
]); ?>

<div style="padding: 25px 0"><?= $content ?></div>

<?php $this->endContent() ?>