<?php
/**
 * Copyright (c) 2016. OSKR JSC "NIAEP"
 */

namespace common\components\actions;

use yii\helpers\Url;

/**
 * Class DeleteAction
 * @package common\components\actions
 */
class DeleteAction extends BaseAction
{
    /**
     * @var string
     */
    public $successMessage = 'Данные успешно удалены';

    /**
     * @inheritdoc
     */
    public function run($id)
    {
        return parent::run($id);
    }

    protected function setModel($id)
    {
        $this->_model = $this->findModel($id);
    }

    protected function processRequest()
    {
        if ($this->_model->delete()) {
            \Yii::$app->session->setFlash('success', $this->successMessage);
        }

        return $this->controller->redirect(Url::previous());
    }
}