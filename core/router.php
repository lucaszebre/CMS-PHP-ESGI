<?php

declare(strict_types=1);

class Router
{

    private array $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }


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

    public function dispatch(string $path): void
    {
        $requestPath = $this->normalizePath($path);
        $method = strtoupper($_SERVER["REQUEST_METHOD"]);

        foreach ($this->routes as $route) {
            $routePath = $this->normalizePath($route["path"]);

            if (
                !preg_match("#^{$routePath}$#", $requestPath) ||
                $route['method'] !== $method
            ) {
                continue;
            }

            [$class, $function] = $route['controller'];

            $controllerInstance = new $class;

            $controllerInstance->{$function}();
            return;
        }

        http_response_code(404);
        echo "Page not found";
    }
}
