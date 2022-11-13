<?php

    require_once './libs/router.php';
    require_once './app/controllers/jobs-api.controller.php';
    require_once './app/controllers/auth-api.controller.php';

    $router = new Router();

    $router->addRoute('jobs', 'GET', 'JobsApiController', 'get');
    $router->addRoute('jobs/:ID', 'GET', 'JobsApiController', 'getJob');
    $router->addRoute('jobs/:ID', 'DELETE', 'JobsApiController', 'delete');
    $router->addRoute('jobs', 'POST', 'JobsApiController', 'insert');
    $router->addRoute('jobs/:ID', 'PUT', 'JobsApiController', 'modify');
    $router->addRoute('users/token', 'GET', 'AuthApiController', 'getToken');

    $router->route($_GET["resource"], $_SERVER['REQUEST_METHOD']);