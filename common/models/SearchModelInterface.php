<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 13.10.15
 * Time: 15:03
 */
namespace common\models;

use yii\data\BaseDataProvider;

interface SearchModelInterface {

    /**
     * @return BaseDataProvider
     */
    public function search();

    /**
     * @param $params array
     * @return bool
     */
    public function load($params);
}