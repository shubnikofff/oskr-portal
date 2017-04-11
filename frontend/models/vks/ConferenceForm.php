<?php
/**
 * oskr-portal
 * Created: 27.03.17 18:50
 * @copyright Copyright (c) 2017 OSKR NIAEP
 */

namespace frontend\models\vks;

use yii\base\Model;


/**
 * Class ConferenceForm
 *
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 */

class ConferenceForm extends Model
{
    public $mcu;

    public $profile;

    public $audioRecordType;

    public function rules()
    {
        return [
            [['mcu', 'profile', 'audioRecordType'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'mcu' => 'MCU',
            'profile' => 'Профиль',
            'audioRecordType' => 'Тип аудиозаписи'
        ];
    }

    /**
     * Creates new instance
     * @param Conference|null $conference
     * @return ConferenceForm
     */
    public static function make($conference)
    {
        $instance = new self();
        if ($conference instanceof Conference) {
            $instance->mcu = $conference->getMcuId();
            $instance->profile = $conference->getProfileId();
            $instance->audioRecordType = $conference->getAudioRecordTypeId();
        }
        return $instance;
    }
}