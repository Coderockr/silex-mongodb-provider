<?php

namespace Coderockr\Mongodb;

use Silex\Application;
use Silex\ServiceProviderInterface;
use MongoDB\Driver\Manager;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['mongodbs.options.initializer'] = $app->protect(function () use ($app) {
            static $initialized = false;

            if ($initialized) {
                return;
            }

            $initialized = true;

            if (!isset($app['mongodbs.options'])) {
                $app['mongodbs.options'] = [
                    'default' => isset($app['mongodb.options']) ? $app['mongodb.options'] : []
                ];
            }

            $tmp = [];
            foreach ($app['mongodbs.options'] as $name => $options) {
                $tmp[$name] = array_replace($app['mongodb.default_options'], $options);

                if (!isset($app['mongodbs.default'])) {
                    $app['mongodbs.default'] = $name;
                }
            }

            $app['mongodbs.options'] = $tmp;
        });

        $app['mongodbs'] = $app->share(function (Application $app) {
            $app['mongodbs.options.initializer']();

            $container = new \Pimple();
            foreach ($app['mongodbs.options'] as $name => $options) {
                $container[$name] = $container->share(function () use ($options) {
                    return new Manager($options['uri'], $options['options'], $options['driverOptions']);
                });
            }

            return $container;
        });

        $app['mongodb.default_options'] = [
            'uri' => 'mongodb://localhost:27017',
            'options' => [],
            'driverOptions' => []
        ];

        // shortcuts for the "first" MongoDB
        $app['mongodb'] = $app->share(function (Application $app) {
            $dbs = $app['mongodbs'];
            return $dbs[$app['mongodbs.default']];
        });
    }

    public function boot(Application $app)
    {}
}
