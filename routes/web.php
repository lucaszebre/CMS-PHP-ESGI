<?php

use App\Controllers\AuthController;
use App\Controllers\SiteController;
use App\Core\Router;

function registerWebRoutes(Router $router): void
{
    $router->get('/', [SiteController::class, 'home']);

    $router->get('/login', [AuthController::class, "showLogin"]);
    $router->post('/login', [AuthController::class, 'login']);

    $router->get('/register', [AuthController::class, "showRegister"]);
    $router->post('/register', [AuthController::class, 'register']);

    $router->get('/logout', [AuthController::class, 'logout']);
}
