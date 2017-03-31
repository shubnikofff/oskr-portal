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

    public $audiRecordType;

    public function rules()
    {
        return [
            [['mcu', 'profile', 'audioRecordType'], 'required']
        ];
    }

    public function attributeLabels()
    {
        return [
            'mcu' => 'MCU',
            'profile' => 'Профиль',
            'audiRecordType' => 'Тип аудиозаписи'
        ];
    }

    /**
     * Creates new instance
     * @param Conference $conference
     * @return ConferenceForm
     */
    public static function create(Conference $conference)
    {
        $form = new self();
        if ($conference) {
            $form->mcu = $conference->getMcuId();
            $form->profile = $conference->getProfileId();
            $form->audiRecordType = $conference->getAudioRecordTypeId();
        }
        return $form;
    }
}