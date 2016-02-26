<?php

class m160226_064544_rename_company_collection extends \yii\mongodb\Migration
{
    const NAME_NEW = 'roomGroup';
    const NAME_OLD = 'company';
    /**
     * @var \yii\mongodb\Database
     */
    private $_db;
    /**
     * @var \yii\mongodb\Database
     */
    private $_adminDB;

    public function init()
    {
        parent::init();
        $this->_db = Yii::$app->get('mongodb')->getDatabase();
        $this->_adminDB = Yii::$app->get('mongodb')->getDatabase('admin');
    }

    public function up()
    {
        $this->_adminDB->executeCommand([
            'renameCollection' => $this->_db->name . '.' .self::NAME_OLD,
            'to' => $this->_db->name . '.' .self::NAME_NEW
        ]);
        $collection = $this->_db->getCollection(self::NAME_NEW);
        $collection->update([],['$rename' => [
            'address' => 'description'
        ]]);

    }

    public function down()
    {
        $this->_adminDB->executeCommand([
            'renameCollection' => $this->_db->name . '.' .self::NAME_NEW,
            'to' => $this->_db->name . '.' .self::NAME_OLD,
        ]);

        $collection = $this->_db->getCollection(self::NAME_OLD);
        $collection->update([],['$rename' => [
            'description' => 'address'
        ]]);
    }
}
