<?php
namespace Coderockr\Mongodb\Test;

use Silex\Application;
use Silex\WebTestCase;
use Coderockr\Mongodb\ServiceProvider as MongodbServiceProvider;

class ServiceProviderTest extends WebTestCase
{
    /**
     * @test
     */
    public function singleConnection()
    {
        var_dump('a');
        $app = $this->createApplication();
        $app->register(new MongodbServiceProvider(), [
            'mongodb.options' => [
                'uri' => 'mongodb://localhost:27017',
                'options' => [],
                'driverOptions' => []
            ]
        ]);

        $orm = $app['orm'];
        $this->assertSame($app['ems']['default'], $orm);
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

        $orm = $app['orm'];
        $this->assertSame($app['ems']['sqlite1'], $orm);
    }

    public function createApplication()
    {
        $app = new Application();
        $app['debug'] = true;
        $app['exception_handler']->disable();
        return $app;
    }
}
