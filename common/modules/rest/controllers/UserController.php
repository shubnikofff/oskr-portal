<?php
/**
 * Copyright (c) 2016. OSKR JSC "NIAEP" 
 */

namespace common\modules\rest\controllers;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use common\modules\rest\models\User;

/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * UserController
 */

class UserController extends ActiveController
{
    public $modelClass = 'common\modules\rest\models\User';
    
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create'], $actions['update'], $actions['delete']);

        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }

    /**
     * @return ActiveDataProvider
     */
    public function prepareDataProvider()
    {
        $query = User::find();

        $name = \Yii::$app->request->get('name');
        if ($name !== null) {
            list($last, $first, $middle) = explode(' ', $name);
            $query->andFilterWhere(['like', 'lastName', $last]);
            $query->andFilterWhere(['like', 'firstName', $first]);
            $query->andFilterWhere(['like', 'middleName', $middle]);
        }

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }
}