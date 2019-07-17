<?php

use SallePW\SlimApp\Controller\Middleware\SessionMiddleware;
use SallePW\SlimApp\Controller\FileController;
use SallePW\SlimApp\Controller\RegisterController;
use SallePW\SlimApp\Controller\LoginController;
use SallePW\SlimApp\Controller\ProfileController;
use SallePW\SlimApp\Controller\HomeController;
use SallePW\SlimApp\Controller\SearchController;
use SallePW\SlimApp\Controller\myProductsController;
use SallePW\SlimApp\Controller\AddItemController;
use SallePW\SlimApp\Controller\OverviewController;
use SallePW\SlimApp\Controller\BuyController;
use SallePW\SlimApp\Controller\DeleteItemController;


$app->get('/', HomeController::class . ':loadAction');

$app->get('/files', FileController::class . ':indexAction');

$app
    ->post('/files', FileController::class . ':uploadAction')
    ->setName('upload');

$app->get('/profile', ProfileController::class . ':formAction')
    ->setName('profile');

$app
    ->post('/image', ProfileController::class . ':uploadAction')
    ->setName('image');
$app
    ->get('/delete', ProfileController::class . ':deleteAction')
    ->setName('delete');
$app
    ->post('/update', ProfileController::class . ':registerAction')
    ->setName('update');

$app->get('/login', LoginController::class . ':formAction');

$app
    ->post('/login', LoginController::class . ':loginAction')
    ->setName('login');

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

$app
    ->post('/updateItem', OverviewController::class . ':updateItem')
    ->setName('updateItem');

$app->get('/buy', BuyController::class . ':loadAction');

$app
    ->post('/buy', BuyController::class . ':loadAction')
    ->setName('buy');

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

$app->get('/deleteItem', DeleteItemController::class . ':deleteAction');

$app
    ->post('/deleteItem', DeleteItemController::class . ':deleteAction')
    ->setName('deleteItem');

$app->add(SessionMiddleware::class);
