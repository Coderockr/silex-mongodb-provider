<?php
namespace Coderockr\Mongodb;

use Silex\Application;
use Silex\ServiceProviderInterface;
use MongoDB\Driver\Manager;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['mongodb.connection'] = [
            // 'uri' => "mongodb://localhost:27017",
            // 'options' => [],
            // 'driverOptions' => []
        ];

        $app['mongodb.manager'] = $app->share(function ($app) {
            return new Manager($app['mongodb.connection']['uri']/*, $app['mongodb.connection']['options'], $app['mongodb.connection']['driverOptions']*/);
        });
    }

    public function boot(Application $app)
    {}
}
