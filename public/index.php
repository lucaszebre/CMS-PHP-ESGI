<?php

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once dirname(__DIR__) . '/core/autoload.php';
require_once dirname(__DIR__) . '/routes/web.php';


$router = new Router();


registerWebRoutes($router);


$uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$router->dispatch($uriPath);
