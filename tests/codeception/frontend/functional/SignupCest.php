<?php

namespace tests\codeception\frontend\functional;

use tests\codeception\frontend\_pages\SignupPage;
use common\models\User;
use tests\codeception\frontend\FunctionalTester;

class SignupCest
{

    /**
     * This method is called before each cest class test method
     * @param \Codeception\Event\TestEvent $event
     */
    public function _before($event)
    {
    }

    /**
     * This method is called after each cest class test method, even if test failed.
     * @param \Codeception\Event\TestEvent $event
     */
    public function _after($event)
    {
        User::deleteAll([
            'email' => 'tester.email@example.com',
            'username' => 'tester',
        ]);
    }

    /**
     * This method is called when test fails.
     * @param \Codeception\Event\FailEvent $event
     */
    public function _fail($event)
    {

    }

    /**
     *
     * @param \codeception_frontend\FunctionalTester $I
     * @param FunctionalTester $I
     * @param \Codeception\Scenario $scenario
     */
    public function testUserSignup($I, $scenario)
    {
        $I->wantTo('ensure that signup works');

        $signupPage = SignupPage::openBy($I);
        $I->see('Signup', 'h1');
        $I->see('Please fill out the following fields to signup:');

        $I->amGoingTo('submit signup form with no data');
        //$I->see('Signup', 'h1');
        //$I->see('Please fill out the following fields to signup:');

        $I->amGoingTo('submit signup form with no configs');

        $signupPage->submit([]);

        $I->expectTo('see validation errors');
        $I->see('Username cannot be blank.', '.help-block');
        $I->see('Email cannot be blank.', '.help-block');
        $I->see('Password cannot be blank.', '.help-block');
        $I->see('Необходимо заполнить «Имя пользователя (логин)».', '.help-block');
        $I->see('Необходимо заполнить «Email».', '.help-block');
        $I->see('Необходимо заполнить «Пароль».', '.help-block');
        $I->see('Необходимо заполнить «Повторно пароль».', '.help-block');
        $I->see('Необходимо заполнить «Фамилия».', '.help-block');
        $I->see('Необходимо заполнить «Подразделение».', '.help-block');
        $I->see('Необходимо заполнить «Должность».', '.help-block');
        $I->see('Необходимо заполнить «Внутренний телефон».', '.help-block');

        $I->amGoingTo('submit signup form with not correct email');
        $signupPage->submit([
            'username' => 'tester',
            'email' => 'tester.email',
            'password' => 'tester_password',
        ]);

        $I->expectTo('see that email address is wrong');
        $I->dontSee('Username cannot be blank.', '.help-block');
        $I->dontSee('Password cannot be blank.', '.help-block');
        $I->see('Email is not a valid email address.', '.help-block');

        $I->amGoingTo('submit signup form with correct email');
        $I->dontSee('Необходимо заполнить «Имя пользователя (логин)».', '.help-block');
        $I->dontSee('Необходимо заполнить «Пароль».', '.help-block');
        $I->see('Значение «Email» не является правильным email адресом.', '.help-block');

        $I->amGoingTo('submit signup form with not correct password_repeat');
        $signupPage->submit([
            'username' => 'tester',
            'email' => 'tester.email@example.com',
            'password' => 'tester_password',
            'password_repeat' => 'password_tester',
        ]);
        $I->expectTo('see that password_repeat is wrong');
        $I->see('Значение «Пароль» должно быть повторено в точности.', '.help-block');

        $I->amGoingTo('submit signup form with correct data');
        $signupPage->submit([
            'username' => 'tester',
            'email' => 'tester.email@example.com',
            'password' => 'tester_password',
            'password_repeat' => 'tester_password',
            'lastName' => 'LastName',
            'firstName' => 'FirstName',
            'middleName' => 'MiddleName',
            'division' => 'Division',
            'post' => 'Post',
            'phone' => '12-34',
            'mobile' => '(910) 123-45-67'

        ]);

        $I->expectTo('see that user is created');
        $I->seeRecord('common\models\User', [
            'username' => 'tester',
            'email' => 'tester.email@example.com',
        ]);

        $I->seeRecord('common\models\UserProfile', [
            'lastName' => 'LastName',
            'firstName' => 'FirstName',
            'middleName' => 'MiddleName',
            'division' => 'Division',
            'post' => 'Post',
            'phone' => '12-34',
            'mobile' => '(910) 123-45-67'
        ]);
        $I->expectTo('see that user logged in');
        $I->seeLink('Logout (tester)');
    }
}
