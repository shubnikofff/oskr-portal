<?php
/**
 * Copyright (c) 2016. OSKR JSC "NIAEP"
 */

namespace common\components\actions;
/**
 * Class ViewAction
 * @package common\components\actions
 */
class ViewAction extends BaseAction
{
    /**
     * @var string
     */
    public $view = 'view';

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
        $renderMethod = \Yii::$app->request->isAjax ? 'renderAjax' : 'render';

        return call_user_func([$this->controller, $renderMethod], $this->view, ['model' => $this->_model]);
    }
}