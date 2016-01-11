<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 06.10.15
 * Time: 15:01
 */

namespace common\components\actions;

use yii\base\Action;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\base\InvalidConfigException;
use yii\db\BaseActiveRecord;

/**
 * Class CrudAction
 * @package common\components\actions
 */
abstract class CrudAction extends Action
{
    const URL_NAME_INDEX_ACTION = 'index-action';
    /**
     * @var BaseActiveRecord
     */
    protected $_model;
    /**
     * @var string
     */
    public $modelClass;
    /**
     * @var string
     */
    public $scenario = 'default';
    /**
     * @var string
     */
    public $permission;
    /**
     * @var array
     */
    public $permissionParams;
    /**
     * @var string
     */
    public $view;
    /**
     * @var array|string
     */
    public $redirectUrl;
    /**
     * @var string Message that displayed in Alert widget if action success
     */
    public $successMessage;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (!$this->redirectUrl) {
            $this->redirectUrl = Url::previous(self::URL_NAME_INDEX_ACTION);
        }

        if (!is_subclass_of($this->modelClass, BaseActiveRecord::className())) {
            throw new InvalidConfigException("Property 'modelClass': given class extend '" . BaseActiveRecord::className() . "'");
        }
    }

    /**
     * @param null $id
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    protected function run($id = null)
    {
        /** @var BaseActiveRecord $modelClass */
        $modelClass = $this->modelClass;
        if ($id !== null) {
            $this->_model = $modelClass::findOne($id);
            if (is_null($this->_model)) {
                throw new NotFoundHttpException("Страница не найдена.");
            }
        } else {
            $this->_model = new $modelClass();
        }

        $this->_model->scenario = $this->scenario;

        $this->checkPermission();
    }

    protected function checkPermission()
    {
        if ($this->permission) {
            $permissionParams = $this->permissionParams ? $this->permissionParams : ['object' => $this->_model];
            if (!\Yii::$app->user->can($this->permission, $permissionParams)) {
                throw new ForbiddenHttpException("Операция запрещена. Недостаточно прав.");
            }
        }
    }
}