<?php

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once dirname(__DIR__) . '/core/router.php';
require_once dirname(__DIR__) . '/app/controllers/siteController.php';
require_once dirname(__DIR__) . '/app/controllers/authController.php';

$router = new Router([]);
$router->add('GET', '/', [SiteController::class, 'home']);
$router->add('GET', '/about', [SiteController::class, 'about']);
$router->add('POST', '/login', [AuthController::class, 'login']);
$router->add('GET', '/logout', [AuthController::class, 'logout']);
$router->add('GET', '/login', [SiteController::class, 'login']);
$router->add('POST', '/register', [AuthController::class, 'register']);
$router->add('GET', '/register', [SiteController::class, 'register']);

$uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$router->dispatch($uriPath);
