<?php
/**
 * Copyright (c) 2016. OSKR JSC "NIAEP"
 */

namespace common\components\actions;

use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\db\BaseActiveRecord;

/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * BaseAction
 */
abstract class BaseAction extends Action
{
    /**
     * @var string
     */
    public $modelClass;
    /**
     * @var string
     */
    public $permission;
    /**
     * @var array
     */
    public $permissionParams = [];
    /**
     * @var BaseActiveRecord
     */
    protected $_model;

    public function init()
    {
        parent::init();

        if (!is_subclass_of($this->modelClass, BaseActiveRecord::className())) {
            throw new InvalidConfigException("Provided model class '{$this->modelClass}' must extend BaseActiveRecord class");
        }
    }

    /**
     * @param $id
     * @return null|static
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        /**
         * @var $modelClass BaseActiveRecord
         */
        $modelClass = $this->modelClass;
        $model = $modelClass::findOne($id);

        if (is_null($model)) {
            throw new NotFoundHttpException('Запрашиваемая страница не найдена.');
        }

        return $model;
    }

    protected function run($id = null)
    {
        $this->setModel($id);

        if (isset($this->permission)) {
            if (!array_key_exists('object', $this->permissionParams)) {
                $this->permissionParams['object'] = $this->_model;
            }
            if (!\Yii::$app->user->can($this->permission, $this->permissionParams)) {
                throw new ForbiddenHttpException("Выполение операции запрещено.");
            }
        }

        return $this->processRequest();
    }

    abstract protected function setModel($id);

    abstract protected function processRequest();
}