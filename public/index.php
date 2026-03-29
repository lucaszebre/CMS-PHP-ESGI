<?php

declare(strict_types=1);

use App\Core\Router;
use App\Core\Request;

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once dirname(__DIR__) . '/core/autoload.php';
require_once dirname(__DIR__) . '/routes/web.php';


$router = new Router();
$request = Request::fromGlobals();


registerWebRoutes($router);


$router->dispatch($request);
