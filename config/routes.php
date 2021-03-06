<?php

use App\Application\Rest\AtualizaPostAction;
use App\Application\Rest\BuscaPostAction;
use App\Application\Rest\BuscaPostPorFiltroAction;
use App\Application\Rest\BuscaUserAction;
use App\Application\Rest\CadastraPostAction;
use App\Application\Rest\CadastraUserAction;
use App\Application\Middleware;
use App\Application\Rest\ExcluiPostAction;
use App\Application\Rest\ExcluiUserAction;
use App\Application\Rest\ListaPostsAction;
use App\Application\Rest\ListaUsersAction;
use App\Application\Rest\LoginAction;
use DI\Container;

/** @var Container $container */
$container = $app->getContainer();

$app->add(new Middleware\JsonBodyParserMiddleware());

$app->post('/login', new LoginAction($container));

$app->post('/user', new CadastraUserAction($container));
$app->get('/user', new ListaUsersAction($container));
$app->get('/user/{id}', new BuscaUserAction($container));
$app->delete('/user/me', new ExcluiUserAction($container));

$app->post('/post', new CadastraPostAction($container));
$app->put('/post/{id}', new AtualizaPostAction($container));
$app->get('/post/search', new BuscaPostPorFiltroAction($container));
$app->get('/post/{id}', new BuscaPostAction($container));
$app->get('/post', new ListaPostsAction($container));
$app->delete('/post/{id}', new ExcluiPostAction($container));
