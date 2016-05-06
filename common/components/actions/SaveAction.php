<?php
/**
 * Copyright (c) 2016. OSKR JSC "NIAEP"
 */

namespace common\components\actions;

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\Response;

/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * SaveAction
 */
abstract class SaveAction extends BaseAction
{
    /**
     * @var string
     */
    public $view;
    /**
     * @var string
     */
    public $successMessage;

    protected function processRequest()
    {
        if ($this->_model->load(\Yii::$app->request->post())) {

            if (\Yii::$app->request->isAjax) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($this->_model);
            }

            if ($this->_model->save()) {
                \Yii::$app->session->setFlash('success', $this->successMessage);
                return $this->controller->redirect(Url::previous());
            }
        }

        return $this->controller->render($this->view, ['model' => $this->_model]);
    }

}