<?php
namespace frontend\tests\unit\models\vks;

use common\fixtures\RequestFixture;
use frontend\models\vks\Request;
use MongoDB\BSON\UTCDateTime;

class RequestTest extends \Codeception\Test\Unit
{
    /**
     * @var \frontend\tests\UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->tester->haveFixtures([
            'request' => [
                'class' => RequestFixture::class,
                'dataFile' => codecept_data_dir() . 'request.php'
            ]
        ]);
    }

    protected function _after()
    {

    }

    public function testGenerateCorrectNumber()
    {
        $this->assertEquals(100, Request::generateNumber(new UTCDateTime()));
        $this->assertEquals(105, Request::generateNumber(new UTCDateTime(strtotime("2017-01-15 00:00:00"))));
    }
}