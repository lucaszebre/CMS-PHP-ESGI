<?php

declare(strict_types=1);

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

    public function dispatch(Request $request): void
    {
        $requestPath = $this->normalizePath($request->path());
        $method = $request->method();

        foreach ($this->routes as $route) {
            $routePath = $this->normalizePath($route['path']);

            if (
                !preg_match("#^{$routePath}$#", $requestPath) ||
                $route['method'] !== $method
            ) {
                continue;
            }

            [$class, $function] = $route['controller'];
            $controllerInstance = new $class();
            $controllerInstance->{$function}($request);
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
