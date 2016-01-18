<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 06.10.15
 * Time: 15:00
 */

namespace common\components\actions;

use yii\widgets\ActiveForm;
use yii\web\Response;
/**
 * Class UpdateAction
 * @package common\components\actions
 */
class UpdateAction extends CrudAction
{
    /**
     * Which method will be run after model load
     * First element is a method name
     * Second element is an array of method params
     * @var array
     */
    public $modelMethod = ['save'];
    /**
     * @inheritdoc
     */
    public $view = 'update';
    /**
     * @inheritdoc
     */
    public $successMessage = 'Данные успешно сохранены';
    /**
     * @inheritdoc
     */
    public function run($id)
    {
        parent::run($id);

        $model = $this->_model;

        $request = \Yii::$app->request;

        if ($this->_model->load($request->post())) {

            if ($request->isAjax) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($this->_model);
            }

            $methodParam =  $this->modelMethod[1];

            $methodResult = $methodParam ? call_user_func([$model, $this->modelMethod[0]], $methodParam) : call_user_func([$model, $this->modelMethod[0]]);

            if($methodResult) {
                \Yii::$app->session->setFlash('success', $this->successMessage);
                return $this->controller->redirect($this->redirectUrl);
            }

        }

        return $this->controller->render($this->view, ['model' => $model]);

    }
}