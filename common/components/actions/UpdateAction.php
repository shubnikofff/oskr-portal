<?php
/**
 * Copyright (c) 2016. OSKR JSC "NIAEP"
 */

namespace common\components\actions;

/**
 * Class UpdateAction
 * @package common\components\actions
 */
class UpdateAction extends SaveAction
{
    /**
     * @inheritdoc
     */
    public $view = 'update';
    /**
     * @inheritdoc
     */
    public $successMessage = 'Данные успешно сохранены';
    /**
     * @var string
     */
    public $scenario;

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

        if (isset($this->scenario)) {
            $this->_model->scenario = $this->scenario;
        }
    }
}