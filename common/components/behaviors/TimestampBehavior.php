<?php
/**
 * Copyright (c) 2016. OSKR JSC "NIAEP"
 */

namespace common\components\behaviors;


/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * TimestampBehavior
 */

class TimestampBehavior extends \yii\behaviors\TimestampBehavior
{
    public $createdAtAttribute = 'createdAt';

    public $updatedAtAttribute = 'updatedAt';

    public function init()
    {
        parent::init();

        $this->value = new \MongoDate();
    }
}