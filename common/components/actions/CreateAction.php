<?php
/**
 * Copyright (c) 2016. OSKR JSC "NIAEP"
 */

namespace common\components\actions;

/**
 * Class CreateAction
 * @package common\components\actions
 */
class CreateAction extends SaveAction
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
     * @var string
     */
    public $scenario;

    /**
     * @inheritdoc
     */
    public function run()
    {
        return parent::run();
    }

    protected function setModel($id)
    {
        $this->_model = new $this->modelClass();

        if (isset($this->scenario)) {
            $this->_model->scenario = $this->scenario;
        }
    }

}