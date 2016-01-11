<?php
/**
 * teleport
 * Created: 03.12.15 16:27
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */

namespace frontend\models\user;

/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * UpdatePasswordForm
 */
class UpdatePasswordForm extends AccountForm
{
    /**
     * @inheritDoc
     */
    public function scenarios()
    {
        return [
            'default' => ['currentPassword', 'password', 'password_repeat'],
        ];
    }
    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'password' => 'Новый пароль',
            'password_repeat' => 'Новый пароль повторно',
        ]);
    }
    /**
     * @return bool
     */
    public function updatePassword()
    {
        if ($this->validate()) {
            $user = $this->_user;
            $user->setPassword($this->password);
            return $user->save();
        }
        return false;
    }
}