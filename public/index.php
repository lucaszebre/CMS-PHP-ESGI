<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/core/router.php';
require_once dirname(__DIR__) . '/app/controllers/siteController.php';

$router = new Router([]);
$router->add('GET', '/', [SiteController::class, 'home']);
$router->add('GET', '/about', [SiteController::class, 'about']);

$uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$router->dispatch($uriPath);
