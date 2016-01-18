<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 06.10.15
 * Time: 16:12
 */

namespace common\components\actions;
/**
 * Class DeleteAction
 * @package common\components\actions
 */
class DeleteAction extends CrudAction
{
    public $successMessage = 'Данные удалены';
    /**
     * @inheritdoc
     */
    public function run($id)
    {
        parent::run($id);

        $this->_model->delete();

        \Yii::$app->session->setFlash('success', $this->successMessage);

        return $this->controller->redirect($this->redirectUrl);
    }
}