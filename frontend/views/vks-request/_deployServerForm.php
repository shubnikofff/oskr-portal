<?php
/**
 * teleport
 * Created: 22.12.15 16:38
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */
use yii\widgets\ActiveForm;
use common\models\vks\DeployServer;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * @var $this \yii\web\View
 * @var $model \frontend\models\vks\Request
 */
?>

<div class="row">

    <?php $form = ActiveForm::begin([
        'id' => 'deploy-server-form',
        'action' => Url::to(['set-deploy-server', 'id' => (string)$model->primaryKey])]) ?>

    <?php $servers = DeployServer::find()->asArray()->all();
    $items = ArrayHelper::map($servers, function ($item) {
        return (string)$item['_id'];
    }, 'name'); ?>

    <?= $form->field($model, 'deployServerId', ['options' => ['class' => 'col-lg-4']])->dropDownList($items, [
        'id' => 'deploy-server-id',
        'prompt' => $model->getAttributeLabel('deployServerId')
    ])->label(false) ?>

    <?php ActiveForm::end() ?>

</div>

<?php \frontend\assets\vks\DeployServerFormAsset::register($this);
$options = \yii\helpers\Json::encode(['inputSelector' => '#deploy-server-id']);
$this->registerJs("$('#deploy-server-form').deployServerForm({$options});");
?>