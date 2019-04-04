<?php

$app
    ->get('/hello/{name}', 'SallePW\SlimApp\Controller\HelloController:indexAction')
    ->add('SallePW\SlimApp\Controller\Middleware\TestMiddleware')->add('SallePW\SlimApp\Controller\Middleware\Middleware2');
