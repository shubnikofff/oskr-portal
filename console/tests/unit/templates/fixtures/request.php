<?php
/**
 * oskr-portal
 * Created: 19.01.17 14:49
 * @copyright Copyright (c) 2017 OSKR NIAEP
 *
 * @var $faker Faker\Generator
 * @var $index integer
 */
return [
    'number' => 100 + $index,
    'topic' => $faker->text(),
    'date' => new \MongoDB\BSON\UTCDateTime(strtotime("2017-01-15 00:00:00")),
];