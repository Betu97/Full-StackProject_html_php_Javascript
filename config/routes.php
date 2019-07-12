<?php

use SallePW\SlimApp\Controller\Middleware\Middleware2;
use SallePW\SlimApp\Controller\FlashController;
use SallePW\SlimApp\Controller\HelloController;
use SallePW\SlimApp\Controller\Middleware\TestMiddleware;
use SallePW\SlimApp\Controller\Middleware\SessionMiddleware;
use SallePW\SlimApp\Controller\FileController;
use SallePW\SlimApp\Controller\RegisterController;
use SallePW\SlimApp\Controller\LoginController;
use SallePW\SlimApp\Controller\ProfileController;
use SallePW\SlimApp\Controller\UserController;
use SallePW\SlimApp\Controller\HomeController;
use SallePW\SlimApp\Controller\SearchController;
use SallePW\SlimApp\Controller\myProductsController;
use SallePW\SlimApp\Controller\AddItemController;
use SallePW\SlimApp\Controller\OverviewController;

$app
    ->get('/hello/{name}', HelloController::class)
    ->add(TestMiddleware::class);

$app->get('/flash', FlashController::class);

$app->get('/files', FileController::class . ':indexAction');

$app
    ->post('/files', FileController::class . ':uploadAction')
    ->setName('upload');

$app->get('/profile', ProfileController::class . ':formAction');

$app
    ->post('/profile', ProfileController::class . ':uploadAction')
    ->setName('profile');
$app
    ->get('/delete', ProfileController::class . ':deleteAction')
    ->setName('delete');

$app->get('/login', LoginController::class . ':formAction');

$app
    ->post('/login', LoginController::class . ':loginAction')
    ->setName('login');

$app->post('/users', UserController::class . ':registerAction');

$app->get('/home', HomeController::class . ':loadAction')
    ->setName('home');

$app->get('/signOut', HomeController::class . ':signOutAction')
    ->setName('signOut');

$app->get('/myProducts', myProductsController::class . ':loadAction')
    ->setName('myProducts');

$app->get('/overview', OverviewController::class . ':loadAction')
    ->setName('overview');

$app
    ->post('/overview', OverviewController::class . ':loadAction')
    ->setName('overview');

$app->get('/register', RegisterController::class . ':formAction');

$app
    ->post('/register', RegisterController::class . ':registerAction')
    ->setName('register');

$app->get('/addItem', AddItemController::class . ':formAction');

$app
    ->post('/addItem', AddItemController::class . ':registerAction')
    ->setName('addItem');

$app
    ->post('/search', SearchController::class . ':loadAction')
    ->setName('search');



$app->add(SessionMiddleware::class);
