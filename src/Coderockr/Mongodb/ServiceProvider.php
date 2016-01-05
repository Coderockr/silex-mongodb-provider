<?php
namespace Coderockr\Mongodb;

use Silex\Application;
use Silex\ServiceProviderInterface;
use MongoDB\Driver\Manager;

class ServiceProvider implements ServiceProviderInterface
{
    const MONGO = 'mongo';
    const MONGO_CONNECTIONS = 'mongo.connections';
    const MONGO_FACTORY = 'mongo.factory';

    public function register(Application $app)
    {
        $app['mongodb.connection'] = [
            'uri' => "mongodb://localhost:27017",
            'options' => [],
            'driverOptions' = []
        ];

        $app['mongodb.manager'] = $app->share(function () use($app) {
            return new Manager($app['mongodb.connection']['uri'], $app['mongodb.connection']['options'], $app['mongodb.connection']['driverOptions']);
        });
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registers
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     */
    public function boot(Application $app)
    {}
}
