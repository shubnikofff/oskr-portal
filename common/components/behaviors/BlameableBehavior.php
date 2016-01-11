<?php
/**
 * teleport
 * Created: 15.10.15 15:27
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */

namespace common\components\behaviors;

use Yii;
/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * BlameableBehavior
 */

class BlameableBehavior extends \yii\behaviors\BlameableBehavior
{
    /**
     * @inheritdoc
     */
    public $createdByAttribute = 'createdBy';

    /**
     * @inheritdoc
     */
    public $updatedByAttribute = 'updatedBy';

    /**
     * @inheritdoc
     */
    protected function getValue($event)
    {
        if ($this->value === null) {
            $user = Yii::$app->get('user', false);
            return $user && !$user->isGuest ? $user->identity->getPrimaryKey() : null;
        } else {
            return call_user_func($this->value, $event);
        }
    }
}