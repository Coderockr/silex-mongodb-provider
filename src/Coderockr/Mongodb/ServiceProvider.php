<?php

namespace Coderockr\Mongodb\;

use Silex\Application;
use Silex\ServiceProviderInterface;
use MongoDB\Driver\Manager;

class ServiceProvider implements ServiceProviderInterface
{
    private $options = [
        'uri' => "mongodb://localhost:27017",
        'options' => [],
        'driverOptions' => []
    ];

    public function __construct(array $options)
    {
        $this->options = array_merge($this->options, $options);
    }

    public function register(Application $app)
    {
        $app['mongodb.manager'] = $app->share(function () {
            return new Manager($this->options['uri'], $this->options['options'], $this->options['driverOptions']);
        });
    }

    public function boot(Application $app)
    {}
}
