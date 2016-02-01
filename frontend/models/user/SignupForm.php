<?php
/**
 * teleport
 * Created: 27.11.15 14:57
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */

namespace frontend\models\user;

use common\models\User;
use common\rbac\SystemRole;

/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * SignupForm
 */
class SignupForm extends AccountForm
{
    /**
     * @inheritDoc
     */
    public function scenarios()
    {
        return [
            'default' => ['username', 'password', 'password_repeat', 'email', 'lastName', 'firstName', 'middleName', 'division', 'post', 'phone', 'mobile'],
        ];
    }
    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'password' => 'Пароль',
            'password_repeat' => 'Пароль повторно',
        ]);
    }
    /**
     * @return bool
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->setPassword($this->password);
            $user->email = $this->email;
            $user->generateAuthKey();
            $user->generateActivateToken();
            $user->status = User::STATUS_NO_ACTIVATE;
            $user->lastName = $this->lastName;
            $user->firstName = $this->firstName;
            $user->middleName = $this->middleName;
            $user->division = $this->division;
            $user->post = $this->post;
            $user->phone = $this->phone;
            $user->mobile = $this->mobile;

            if ($this->sendEmail($user) && $user->save()) {
                $authManager = \Yii::$app->authManager;
                $employeeRole = $authManager->getRole(SystemRole::EMPLOYEE);
                $authManager->assign($employeeRole, $user->primaryKey);
                return true;
            }
        }
        return false;
    }
    /**
     * @param User $user
     * @return bool
     */
    private function sendEmail(User $user)
    {
        $from = \Yii::$app->params['email.admin'];
        return \Yii::$app->mailer->compose([
            'html' => 'confirmSignup-html',
            'text' => 'confirmSignup-text',
        ], ['model' => $user]
        )->setFrom([$from => 'Служба технической поддрежки' . \Yii::$app->name])
            ->setTo($this->email)
            ->setSubject(\Yii::$app->name . ': завершение регистрации')
            ->send();
    }
}