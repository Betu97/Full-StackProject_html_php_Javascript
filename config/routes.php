<?php

use SallePW\SlimApp\Controller\FlashController;
use SallePW\SlimApp\Controller\HelloController;
use SallePW\SlimApp\Controller\Middleware\TestMiddleware;
use SallePW\SlimApp\Controller\Middleware\Middleware2;
use SallePW\SlimApp\Controller\Middleware\SessionMiddleware;

$app
    ->get('/hello/{name}', HelloController::class)
    ->add(TestMiddleware::class)->add(Middleware2::class.":index_action");

$app->get('/flash', FlashController::class);

$app->add(SessionMiddleware::class);
