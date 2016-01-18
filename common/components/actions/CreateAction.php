<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 06.10.15
 * Time: 16:06
 */

namespace common\components\actions;

use yii\widgets\ActiveForm;
use yii\web\Response;
/**
 * Class CreateAction
 * @package common\components\actions
 */
class CreateAction extends CrudAction
{
    /**
     * @inheritdoc
     */
    public $view = 'create';
    /**
     * @inheritdoc
     */
    public $successMessage = 'Данные успешно добавлены';
    /**
     * @inheritdoc
     */
    public function run()
    {
        parent::run();

        $model = $this->_model;

        $request = \Yii::$app->request;

        if ($model->load($request->post())) {

            if ($request->isAjax) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            if($model->save()) {
                \Yii::$app->session->setFlash('success', $this->successMessage);
                return $this->controller->redirect($this->redirectUrl);
            }

        }

        return $this->controller->render($this->view, ['model' => $model]);
    }
}