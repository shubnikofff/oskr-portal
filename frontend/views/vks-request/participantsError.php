<?php
/**
 * teleport
 * Created: 12.11.15 16:07
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */
use yii\bootstrap\Alert;

/**
 * @var $model \common\models\vks\Request
 */

Alert::begin([
    'options' => [
        'class' => 'alert-danger',
    ]
]); ?>

    <p>Мы не можем подобрать для Вас список участников, так как некоторые поля указаны неверно:</p>

    <ul>

        <?php foreach ($model->firstErrors as $error) : ?>

            <li><?= $error ?></li>

        <?php endforeach; ?>

    </ul>

<?php Alert::end() ?>