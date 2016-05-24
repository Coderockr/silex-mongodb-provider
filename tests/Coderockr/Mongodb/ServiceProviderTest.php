<?php
namespace Coderockr\Mongodb\Test;

use Pimple\Container;
use Coderockr\Mongodb\ServiceProvider as MongodbServiceProvider;
use MongoDB\Driver\Manager;

class ServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function singleConnection()
    {
        $app = $this->createApplication();
        $app->register(new MongodbServiceProvider(), [
            'mongodb.options' => [
                'uri' => 'mongodb://localhost:27017',
                'options' => [],
                'driverOptions' => []
            ]
        ]);

        $this->assertSame($app['mongodb'], $app['mongodbs']['default']);
        $this->assertInstanceOf(Manager::class, $app['mongodb']);
        $this->assertArrayHasKey('default', $app['mongodbs.options']);
    }

    /**
     * @test
     */
    public function multipleConnections()
    {
        $app = $this->createApplication();
        $app->register(new MongodbServiceProvider());

        $app['mongodbs.options'] = [
            'conn1' => [
                'uri' => 'mongodb://localhost:27017',
                'options' => [],
                'driverOptions' => []
            ],
            'conn2' => [
                'mongodb://localhost:27017'
            ]
        ];

        $this->assertSame($app['mongodb'], $app['mongodbs']['conn1']);
        $this->assertNotSame($app['mongodb'], $app['mongodbs']['conn2']);
    }

    public function createApplication()
    {
        return new Container();
    }
}
