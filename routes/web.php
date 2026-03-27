<?php

use App\Controllers\AuthController;
use App\Controllers\SiteController;
use App\Controllers\PageController;
use App\Controllers\AdminPageController;
use App\Core\Router;

function registerWebRoutes(Router $router): void
{
    $router->get('/', [SiteController::class, 'home']);

    $router->get('/login', [AuthController::class, 'showLogin']);
    $router->post('/login', [AuthController::class, 'login']);

    $router->get('/register', [AuthController::class, 'showRegister']);
    $router->post('/register', [AuthController::class, 'register']);

    $router->get('/logout', [AuthController::class, 'logout']);

    // Frontoffice
    $router->get('/pages', [PageController::class, 'list']);
    $router->get('/page/{slug}', [PageController::class, 'show']);

    // Backoffice
    $router->get('/admin/pages', [AdminPageController::class, 'index']);
    $router->get('/admin/pages/create', [AdminPageController::class, 'showCreate']);
    $router->post('/admin/pages/create', [AdminPageController::class, 'create']);
    $router->get('/admin/pages/edit/{id}', [AdminPageController::class, 'showEdit']);
    $router->post('/admin/pages/edit/{id}', [AdminPageController::class, 'update']);
    $router->post('/admin/pages/delete/{id}', [AdminPageController::class, 'delete']);
}
