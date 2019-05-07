<?php

use Slim\Flash\Messages;
use Slim\Http\Environment;
use Slim\Http\Uri;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use SallePW\SlimApp\Model\Database\Database;
use Slim\Container;

$container = $app->getContainer();

$container['view'] = function ($c) {
    $view = new Twig(__DIR__ . '/../src/View/templates', [
        'cache' => false,
    ]);

    $router = $c->get('router');

    $uri = Uri::createFromEnvironment(new Environment($_SERVER));

    $view->addExtension(new TwigExtension($router, $uri));

    return $view;
};

$container['flash'] = function () {
    return new Messages();
};

$container['db'] = function (Container $c) {
    return Database::getInstance(
        $c['settings']['db']['username'],
        $c['settings']['db']['password'],
        $c['settings']['db']['host'],
        $c['settings']['db']['dbName']
    );
};

$container['user_repo'] = function (Container $c) {
    return new PDORepository($c->get('db'));
};