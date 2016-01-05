<?php
namespace Coderockr\Mongodb\Test;

use Silex\Application;
use Silex\WebTestCase;
use Coderockr\Mongodb\ServiceProvider as MongodbServiceProvider;
use MongoDB\Driver\Manager;

class ServiceProviderTest extends WebTestCase
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
    }

    /**
     * @test
     */
    public function multipleConnections()
    {
        $app = $this->createApplication();
        $app->register(new MongodbServiceProvider());
        $app['orm.proxy_namespace'] = 'Proxy';
        $app['orm.proxy_dir'] = __DIR__;
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
        $app = new Application();
        $app['debug'] = true;
        $app['exception_handler']->disable();
        return $app;
    }
}
