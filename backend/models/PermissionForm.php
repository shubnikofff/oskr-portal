<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 14.09.15
 * Time: 14:06
 */

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\Query;
use yii\rbac\DbManager;
use yii\rbac\Permission;
use yii\helpers\ArrayHelper;

/**
 * Class PermissionForm
 * @package backend\models
 * @property array $availableRules
 * @property \yii\rbac\Item[] $childrenNames
 */
class PermissionForm extends Model
{
    public $name;
    public $ruleName;
    public $description;

    public function rules()
    {
        return [
            ['ruleName', 'default', 'value' => null],
            ['ruleName', 'checkRuleName'],
            ['description', 'required']
        ];
    }

    public function checkRuleName($attribute)
    {
        /** @var DbManager $authManager */
        $authManager = Yii::$app->getAuthManager();
        $query = (new Query())->select('name')->from($authManager->ruleTable)->indexBy('name');
        if (!in_array($this->$attribute, array_keys($query->all()))) {
            $this->addError($attribute, "Правило '{$this->$attribute}' не найдено");
        }
    }

    public function attributeLabels()
    {
        return [
            'ruleName' => 'Павило',
        ];
    }


    /**
     * @param Permission $permission
     * @return bool
     */
    public function update(Permission $permission)
    {
        if ($this->validate()) {
            $permission->ruleName = $this->ruleName;
            $permission->description = $this->description;
            Yii::$app->authManager->update($permission->name, $permission);
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    public function getAvailableRules()
    {
        $rules[''] = 'Без правила';
        foreach (Yii::$app->getAuthManager()->getRules() as $rule) {
            $rules[$rule->name] = $rule->name;
        }
        return $rules;
    }

    /**
     * @return \yii\rbac\Item[]
     */
    public function getChildrenNames()
    {
        $children =  Yii::$app->authManager->getChildren($this->name);
        return ArrayHelper::getColumn($children, 'description');
    }

}