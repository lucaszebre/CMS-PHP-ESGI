<?php

declare(strict_types=1);

namespace App\Core;

use App\Services\CsrfService;

class Router
{
    private array $routes = [];

    public function __construct() {}


    public function add(string $method, string $path, array $controller): void
    {
        $this->routes[] = [
            "path" => $path,
            "method" => strtoupper($method),
            "controller" => $controller
        ];
    }


    private function normalizePath(string $path): string
    {
        $path = trim($path, '/');
        $path = "/{$path}/";
        $path = preg_replace('#[/]{2,}#', '/', $path);
        return $path;
    }

    private function matchRoute(string $routePath, string $requestPath): ?array
    {
        $routeParts = explode('/', trim($routePath, '/'));
        $requestParts = explode('/', trim($requestPath, '/'));

        if (count($routeParts) !== count($requestParts)) {
            return null;
        }

        $params = [];

        foreach ($routeParts as $i => $part) {
            if(str_starts_with($part, '{') && str_ends_with($part, '}')) {
                
                $params[trim($part, '{}')] = $requestParts[$i];
            } 
            else if($part !== $requestParts[$i]) {
                return null;
            }
        }

        return $params;
    }

    public function dispatch(Request $request): void
    {
        $requestPath = $this->normalizePath($request->path());
        $method = $request->method();

        if ($method === 'POST') {
            $csrfToken = $request->input('_csrf');
            if (!CsrfService::validate($csrfToken)) {
                http_response_code(403);
                echo 'Invalid CSRF token';
                return;
            }
        }

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $params = $this->matchRoute($route['path'], $requestPath);

            if ($params === null) {
                continue;
            }

            [$class, $function] = $route['controller'];
            $controllerInstance = new $class();
            $controllerInstance->{$function}($request->withParams($params));
            return;
        }

        http_response_code(404);
        echo 'Page not found';
    }



    public function get(string $path, array $controller): void
    {
        $this->add('GET', $path, $controller);
    }

    public function post(string $path, array $controller): void
    {
        $this->add('POST', $path, $controller);
    }
}
