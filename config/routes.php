<?php

use App\Application\Rest\CadastraUserAction;
use App\Application\Middleware;
use App\Application\Rest\LoginAction;
use DI\Container;

/** @var Container $container */
$container = $app->getContainer();

$app->add(new Middleware\JsonBodyParserMiddleware());

$app->post('/login', new LoginAction($container));

$app->post('/user', new CadastraUserAction($container));
