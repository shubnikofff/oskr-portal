<?php

class m151126_115624_create_user_collection extends \yii\mongodb\Migration
{
    public $collectionName = 'user';

    public function up()
    {
        $this->createCollection($this->collectionName);
        $this->createIndex($this->collectionName, 'username', ['unique' => true]);
        $this->createIndex($this->collectionName, 'email', ['unique' => true]);
    }

    public function down()
    {
        $this->dropCollection($this->collectionName);
        return true;
    }
}
