<?php
/**
 * teleport
 * Created: 21.12.15 16:18
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */

namespace common\components\actions;
use yii\web\HttpException;


/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * ModelMethodAction
 */

class ModelMethodAction extends CrudAction
{
    public $modelMethod;

    public function run($id)
    {
        parent::run($id);

        $model = $this->_model;

        $methodParam =  $this->modelMethod[1];

        $methodResult = $methodParam ? call_user_func([$model, $this->modelMethod[0]], $methodParam) : call_user_func([$model, $this->modelMethod[0]]);

        if($methodResult) {
            \Yii::$app->session->setFlash('success', $this->successMessage);
            return $this->controller->redirect($this->redirectUrl);
        } else {
          throw new HttpException('Операция не может быть выполнена');
        }
    }
}