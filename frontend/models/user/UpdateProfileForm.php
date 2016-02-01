<?php
/**
 * teleport
 * Created: 03.12.15 11:09
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */

namespace frontend\models\user;

/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * UpdateProfileForm
 */

class UpdateProfileForm extends AccountForm
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        $user = $this->_user;
        $this->lastName = $user->lastName;
        $this->firstName = $user->firstName;
        $this->middleName = $user->middleName;
        $this->division = $user->division;
        $this->post = $user->post;
        $this->phone = $user->phone;
        $this->mobile = $user->mobile;
    }

    /**
     * @inheritDoc
     */
    public function scenarios()
    {
        return [
            'default' => ['lastName', 'firstName', 'middleName', 'companyId', 'division', 'post', 'phone', 'mobile'],
        ];
    }

    /**
     * @return bool
     */
    public function updateProfile()
    {
        if ($this->validate()) {
            $user = $this->_user;
            $user->lastName = $this->lastName;
            $user->firstName = $this->firstName;
            $user->middleName = $this->middleName;
            $user->division = $this->division;
            $user->post = $this->post;
            $user->phone = $this->phone;
            $user->mobile = $this->mobile;
            return $user->save();
        }
        return false;
    }
}