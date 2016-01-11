<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 27.08.15
 * Time: 14:03
 */

namespace frontend\models\user;

class LoginForm extends \common\models\LoginForm
{
    public $rememberMe = true;

    public function rules()
    {
        return array_merge(parent::rules(),[
            ['rememberMe', 'boolean']
        ]);
    }

    public function attributeLabels()
    {
        return [
            'rememberMe' => 'Запомнить меня'
        ];
    }

    protected function doLogin()
    {
        return \Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
    }
}