# Mongodb Service Provider

## Install

    php composer.phar require coderockr/silex-mongodb-provider

## Usage

    <?php

    require_once __DIR__.'/vendor/autoload.php';

    use Silex\Application;
    use Coderockr\Mongodb\ServiceProvider as MongodbServiceProvider;

    $app = new Application();
    $app->register(new MongodbServiceProvider(), [
        'mongodb.options' => [
            'uri' => 'mongodb://localhost:27017'
        ]
    ]);

    $manager = $app['mongodb']; // or $app['mongodbs']['default'];
    $collection = new MongoDB\Collection($manager, "demo.beers");
    $result = $collection->insertOne(['name' => 'Hinterland', 'brewery' => 'BrewDog']);

    echo "Inserted with Object ID '{$result->getInsertedId()}'";
