<?php
/**
 * teleport.dev
 * Created: 11.02.16 10:00
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace backend\models;

use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\mongodb\ActiveRecord;
use yii\mongodb\Collection;
use yii\mongodb\validators\MongoIdValidator;


/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * OrderSaver
 */
class OrderSaver extends Model
{
    /**
     * Упорадяоченный список id моделей, определенный пользовтелем
     * @var mixed
     */
    public $order;
    /**
     * Разделитель для списка, передаваемого пользовтаелем
     * @var string
     */
    public $delimiter = ',';
    /**
     * @var string
     */
    public $modelClass;
    /**
     * Attribute name where order will store
     * @var string
     */
    public $orderAttribute = 'order';

    public function init()
    {
        parent::init();

        if (!is_subclass_of($this->modelClass, ActiveRecord::className())) {
            throw new InvalidConfigException("Given attribute 'modelClass' must extend '" . ActiveRecord::className() . "'");
        }
    }

    public function rules()
    {
        return [
            ['order', 'filter', 'filter' => function ($value) {
                $ids = [];
                $mongoIdValidator = new MongoIdValidator();
                foreach (explode($this->delimiter, $value) as $modelId) {
                    if ($mongoIdValidator->validate($modelId)) {
                        $ids[] = new \MongoId($modelId);
                    }
                }
                return $ids;
            }],
            ['order', function ($attribute) {
                /** @var ActiveRecord $modelClass */
                $modelClass = $this->modelClass;
                if ($modelClass::find()->count() !== count($this->$attribute)) {
                    $this->addError($attribute, "Неверное количество предоставленных позиций.");
                }
            }]
        ];
    }

    /**
     * @return ActiveRecord[]
     */
    public function getItems()
    {
        /** @var ActiveRecord $modelClass */
        $modelClass = $this->modelClass;
        return $modelClass::find()->orderBy($this->orderAttribute)->asArray()->all();
    }

    public function save()
    {
        /** @var ActiveRecord $modelClass */
        $modelClass = $this->modelClass;
        /** @var Collection $collection */
        $collection = \Yii::$app->get('mongodb')->getCollection($modelClass::collectionName());
        foreach ($this->order as $index => $id) {
            $collection->update(['_id' => $id], [$this->orderAttribute => $index]);
        }
        $this->order = null;
    }
}