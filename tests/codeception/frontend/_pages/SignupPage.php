<?php

namespace tests\codeception\frontend\_pages;

use tests\codeception\frontend\AcceptanceTester;
use tests\codeception\frontend\FunctionalTester;
use \yii\codeception\BasePage;

/**
 * Represents signup page
 * @property AcceptanceTester|FunctionalTester $actor
 */
class SignupPage extends BasePage
{

    public $route = 'site/signup';

    /**
     * @param array $signupData
     */
    public function submit(array $signupData)
    {
        foreach ($signupData as $field => $value) {
            $inputType = $field === 'body' ? 'textarea' : 'input';
            $this->actor->fillField($inputType . '[name="UserForm[' . $field . ']"]', $value);
        }
        $this->actor->click('signup-button');
    }
}
