<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 24.09.15
 * Time: 12:27
 * @var $form \yii\widgets\ActiveForm
 * @var $model \backend\models\RoleForm
 */
?>
<div class="row">

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-body">
                <?= $form->field($model, 'childRoles')->checkboxList($model->availableRoles) ?>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-body">
                <?= $form->field($model, 'childPermissions')->checkboxList($model->availablePermissions) ?>
            </div>
        </div>
    </div>
</div>
