<?php
/**
 * Copyright (c) 2016. OSKR JSC "NIAEP"
 */

namespace common\components\behaviors;

/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * BlameableBehavior
 */

class BlameableBehavior extends \yii\behaviors\BlameableBehavior
{
    public $createdByAttribute = 'createdBy';

    public $updatedByAttribute = 'updatedBy';

    public function init()
    {
        parent::init();

        $this->value = \Yii::$app->user->identity['_id'];
    }


}