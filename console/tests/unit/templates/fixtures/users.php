<?php
/**
 * oskr-portal
 * Created: 16.01.17 15:34
 * @copyright Copyright (c) 2017 OSKR NIAEP
 */
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return ['user' . $index => [
    'name' => $faker->firstName,
    'phone' => $faker->phoneNumber,
    'city' => $faker->city,
    'time' => $faker->dateTime,
    'password' => Yii::$app->getSecurity()->generatePasswordHash('password_' . $index),
    'auth_key' => Yii::$app->getSecurity()->generateRandomString(),
]];