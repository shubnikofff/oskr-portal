<?php

class m160226_092734_rename_participant_collection extends \yii\mongodb\Migration
{
    const NAME_NEW = 'room';
    const NAME_OLD = 'vks.participant';
    /**
     * @var \yii\mongodb\Database
     */
    private $_db;
    /**
     * @var \yii\mongodb\Database
     */
    private $_adminDB;
    /**
     * @var array
     */
    private $_fields;

    public function init()
    {
        parent::init();
        $this->_db = Yii::$app->get('mongodb')->getDatabase();
        $this->_adminDB = Yii::$app->get('mongodb')->getDatabase('admin');
        $this->_fields = [
            'shortName' => 'description',
            'companyId' => 'groupId',
            'ahuConfirmation' => 'bookingAgreement',
            'contact' => 'contactPerson',
        ];
    }

    public function up()
    {
        $this->_adminDB->executeCommand([
            'renameCollection' => $this->_db->name . '.' . self::NAME_OLD,
            'to' => $this->_db->name . '.' . self::NAME_NEW
        ]);

        $collection = $this->_db->getCollection(self::NAME_NEW);

        $collection->update([], ['$rename' => $this->_fields]);

        $collection->update([], ['$set' => ['multipleBooking' => false]]);

        $collection->update([], ['$unset' => [
            'model' => '',
            'gatekeeperNumber' => ''
        ]]);
    }

    public function down()
    {
        $collection = $this->_db->getCollection(self::NAME_NEW);

        $collection->update([], ['$rename' => array_flip($this->_fields)]);

        $collection->update([], ['$unset' => [
            'multipleBooking' => ''
        ]]);

        $this->_adminDB->executeCommand([
            'renameCollection' => $this->_db->name . '.' . self::NAME_NEW,
            'to' => $this->_db->name . '.' . self::NAME_OLD,
        ]);
    }
}
