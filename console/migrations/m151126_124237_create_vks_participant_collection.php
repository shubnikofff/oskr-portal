<?php

class m151126_124237_create_vks_participant_collection extends \yii\mongodb\Migration
{
    public $collectionName = 'vks.participant';

    public function up()
    {
        $this->createCollection($this->collectionName);
    }

    public function down()
    {
        $this->dropCollection($this->collectionName);
        return true;
    }
}
