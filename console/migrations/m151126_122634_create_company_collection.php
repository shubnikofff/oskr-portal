<?php

class m151126_122634_create_company_collection extends \yii\mongodb\Migration
{
    public $collectionName = 'company';

    public function up()
    {
        $this->createCollection($this->collectionName);
        $this->createIndex($this->collectionName, 'name', ['unique' => true]);
    }

    public function down()
    {
        $this->dropCollection($this->collectionName);
        return true;
    }
}
