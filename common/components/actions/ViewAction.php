<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 06.10.15
 * Time: 14:30
 */

namespace common\components\actions;
/**
 * Class ViewAction
 * @package common\components\actions
 */
class ViewAction extends CrudAction
{
    /**
     * @inheritdoc
     */
    public $view = 'view';

    /**
     * @inheritdoc
     */
    public function run($id)
    {
        parent::run($id);

        $viewParam = ['model' => $this->_model];

        return \Yii::$app->request->isAjax ? $this->controller->renderAjax($this->view, $viewParam) : $this->controller->render($this->view, $viewParam);
    }
}