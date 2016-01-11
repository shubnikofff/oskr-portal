<?php
/**
 * teleport
 * Created: 04.12.15 8:57
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */

namespace frontend\models\user;

/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * UpdateEmailForm
 */
class UpdateEmailForm extends AccountForm
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        $this->email = $this->_user->email;
    }

    /**
     * @inheritDoc
     */
    public function scenarios()
    {
        return [
            'default' => ['currentPassword', 'email'],
        ];
    }

    /**
     * @return bool
     */
    public function updateEmail()
    {
        if ($this->validate()) {
            $user = $this->_user;
            $user->email = $this->email;
            return $user->save();
        }
        return false;
    }
}