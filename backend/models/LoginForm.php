<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 27.08.15
 * Time: 10:35
 */

namespace backend\models;

use common\rbac\SystemPermission;
use Yii;

class LoginForm extends \common\models\LoginForm
{
    const NO_PERMISSIONS_FLASH = 'noPermissions';

    protected function doLogin()
    {
        if (Yii::$app->user->login($this->user)) {
            if (Yii::$app->user->can(SystemPermission::ADMIN_LOGIN)) {
                return true;
            } else {
                Yii::$app->user->logout();
            }
        }
        Yii::$app->session->setFlash(self::NO_PERMISSIONS_FLASH, 'У Вас нет достаточных привилегий для доступа к данному ресурсу');
        return false;
    }
}