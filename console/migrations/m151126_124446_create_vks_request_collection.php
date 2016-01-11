<?php

class m151126_124446_create_vks_request_collection extends \yii\mongodb\Migration
{
    public $collectionName = 'vks.request';

    public function up()
    {
        $this->createCollection($this->collectionName);
        $this->createIndex($this->collectionName, ['beginTime' => 1]);
    }

    public function down()
    {
        $this->dropCollection($this->collectionName);
        return true;
    }
}