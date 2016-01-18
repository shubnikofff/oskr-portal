<?php
/**
 * teleport
 * Created: 26.11.15 16:22
 * @copyright Copyright (c) 2015 OSKR NIAEP
 */

namespace console\controllers;

use Yii;
use yii\helpers\Console;
use yii\console\Controller;
use yii\rbac\Item;
use yii\rbac\Permission;
use yii\rbac\Role;
use yii\rbac\Rule;
use yii\validators\StringValidator;
use common\models\User;
use common\rbac\SystemPermission;
use common\rbac\SystemRole;
use common\rbac\OwnerRule;

/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * ApplicationController
 */
class ApplicationController extends Controller
{
    const RBAC_MIGRATION_PATH = 'vendor/yiisoft/yii2/rbac/migrations';

    private $roles;

    private $permissions;

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $this->roles = [
            SystemRole::ADMINISTRATOR => [
                'name' => SystemRole::ADMINISTRATOR,
                'description' => 'Администратор системы'
            ],
            SystemRole::EMPLOYEE => [
                'name' => SystemRole::EMPLOYEE,
                'description' => 'Сотрудник'
            ],
            SystemRole::OSKR => [
                'name' => SystemRole::OSKR,
                'description' => 'Сотрудник ОСКР'
            ],
        ];

        $this->permissions = [
            SystemPermission::ADMIN_LOGIN => [
                'name' => SystemPermission::ADMIN_LOGIN,
                'description' => 'Доступ к административной части системы'
            ],
            SystemPermission::CREATE_REQUEST => [
                'name' => SystemPermission::CREATE_REQUEST,
                'description' => 'Создание заявок'
            ],
            SystemPermission::UPDATE_REQUEST => [
                'name' => SystemPermission::UPDATE_REQUEST,
                'description' => 'Редактирование заявок'
            ],
            SystemPermission::UPDATE_OWN_REQUEST => [
                'name' => SystemPermission::UPDATE_OWN_REQUEST,
                'description' => 'Редактирование только собственых заявок'
            ],
            SystemPermission::CANCEL_REQUEST => [
                'name' => SystemPermission::CANCEL_REQUEST,
                'description' => 'Отмена заявок'
            ],
            SystemPermission::CANCEL_OWN_REQUEST => [
                'name' => SystemPermission::CANCEL_OWN_REQUEST,
                'description' => 'Отмена только собственых заявок'
            ],
            SystemPermission::APPROVE_REQUEST => [
                'name' => SystemPermission::APPROVE_REQUEST,
                'description' => 'Согласование заявок'
            ],
            SystemPermission::DELETE_REQUEST => [
                'name' => SystemPermission::DELETE_REQUEST,
                'description' => 'Удаление заявок'
            ],


        ];
    }

    public function actionInit()
    {
        $this->stdout("Применяю миграции для MySQL\n", Console::FG_BLUE, Console::BOLD);
        Yii::$app->runAction('migrate', ['migrationPath' => self::RBAC_MIGRATION_PATH]);
        $this->stdout("Применяю миграции для MongoDB\n", Console::FG_BLUE, Console::BOLD);
        Yii::$app->runAction('mongodb-migrate');

        $adminUser = $this->createAdminUser();
        $this->initRbac();

        $authManager = Yii::$app->authManager;
        $authManager->assign($authManager->getRole(SystemRole::ADMINISTRATOR), $adminUser->primaryKey);
    }

    /**
     * @param Permission|Role $item
     * @param string $description
     * @param Rule|null $rule
     */
    private function addItemToRbac($item, $description, Rule $rule = null)
    {
        $item->description = $description;
        if ($rule instanceof Rule) {
            $item->ruleName = $rule->name;
        }
        Yii::$app->authManager->add($item);
    }

    /**
     * @param array $data name => description
     * @param Rule $rule
     * @return \yii\rbac\Role
     */
    private function createRole($data, Rule $rule = null)
    {
        $role = Yii::$app->authManager->createRole($data['name']);
        $this->addItemToRbac($role, $data['description'], $rule);
        return $role;
    }

    /**
     * @param array $data name => description
     * @param Rule $rule
     * @return \yii\rbac\Permission
     */
    private function createPermission($data, Rule $rule = null)
    {
        $permission = Yii::$app->authManager->createPermission($data['name']);
        $this->addItemToRbac($permission, $data['description'], $rule);
        return $permission;
    }

    /**
     * @param $ruleClass
     * @return Rule
     */
    private function createRule($ruleClass)
    {
        $rule = new $ruleClass;
        Yii::$app->authManager->add($rule);
        return $rule;
    }

    /**
     * @param Item $item
     * @param Item[] $children
     */
    private function addChildren(Item $item, array $children)
    {
        $authManager = Yii::$app->authManager;
        foreach ($children as $child) {
            $authManager->addChild($item, $child);
        }
    }

    /**
     * @return User
     */
    private function createAdminUser()
    {
        $name = $this->prompt("Введите имя суперпользовтаеля системы", [
            'default' => 'admin'
        ]);

        $password = $this->prompt("Введите пароль суперпользователя системы:", [
            'required' => true,
            'validator' => function ($input) {
                $validator = new StringValidator(['min' => 6]);
                return $validator->validate($input);
            }
        ]);

        $user = new User();
        $user->username = $name;
        $user->setPassword($password);
        $user->status = User::STATUS_ACTIVE;
        $user->email = Yii::$app->params['email.admin'];
        $user->generateAuthKey();
        $user->save();
        return $user;
    }

    protected function initRbac()
    {
        $this->stdout("Создаю правила" . PHP_EOL, Console::FG_BLUE, Console::BOLD);

        $ownerRule = $this->createRule(OwnerRule::className());

        $this->stdout("Создаю привилегии" . PHP_EOL, Console::FG_BLUE, Console::BOLD);

        $adminLoginPermission = $this->createPermission($this->permissions[SystemPermission::ADMIN_LOGIN]);
        $createRequestPermission = $this->createPermission($this->permissions[SystemPermission::CREATE_REQUEST]);
        $updateRequestPermission = $this->createPermission($this->permissions[SystemPermission::UPDATE_REQUEST]);
        $updateOwnRequestPermission = $this->createPermission($this->permissions[SystemPermission::UPDATE_OWN_REQUEST], $ownerRule);
        $cancelRequestPermission = $this->createPermission($this->permissions[SystemPermission::CANCEL_REQUEST]);
        $cancelOwnRequestPermission = $this->createPermission($this->permissions[SystemPermission::CANCEL_OWN_REQUEST], $ownerRule);
        $approveRequestPermission = $this->createPermission($this->permissions[SystemPermission::APPROVE_REQUEST]);
        $deleteRequestPermission = $this->createPermission($this->permissions[SystemPermission::DELETE_REQUEST]);

        $this->stdout("Создаю роли" . PHP_EOL, Console::FG_BLUE, Console::BOLD);

        $adminRole = $this->createRole($this->roles[SystemRole::ADMINISTRATOR]);
        $employeeRole = $this->createRole($this->roles[SystemRole::EMPLOYEE]);
        $oskrRole = $this->createRole($this->roles[SystemRole::OSKR]);

        $this->addChildren($updateOwnRequestPermission, [$updateRequestPermission]);
        $this->addChildren($cancelOwnRequestPermission, [$cancelRequestPermission]);
        $this->addChildren($adminRole, [$adminLoginPermission]);
        $this->addChildren($employeeRole, [$createRequestPermission, $updateOwnRequestPermission, $cancelOwnRequestPermission]);
        $this->addChildren($oskrRole, [$updateRequestPermission, $approveRequestPermission, $cancelRequestPermission, $deleteRequestPermission]);
    }
}