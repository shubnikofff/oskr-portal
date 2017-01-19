<?php
namespace frontend\tests\models\vks;


use common\fixtures\RequestFixture;
use frontend\models\vks\Request;

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
        $this->assertEquals(100, Request::generateNumber(new \MongoDate()));
        $this->assertEquals(105, Request::generateNumber(new \MongoDate(strtotime("2017-01-15 00:00:00"))));
    }
}