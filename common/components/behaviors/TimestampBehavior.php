<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 13.10.15
 * Time: 13:50
 */

namespace common\components\behaviors;

use yii\db\Expression;

//TODO Delete this class
class TimestampBehavior extends \yii\behaviors\TimestampBehavior
{
    /**
     * @inheritdoc
     */
    public $createdAtAttribute = 'createdAt';
    /**
     * @inheritdoc
     */
    public $updatedAtAttribute = 'updatedAt';

    /**
     * @inheritdoc
     */
    protected function getValue($event)
    {
        if ($this->value instanceof Expression) {
            return $this->value;
        } else {
            return $this->value !== null ? call_user_func($this->value, $event) : new \MongoDate();
        }
    }
}