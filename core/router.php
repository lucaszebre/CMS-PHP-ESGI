<?php

class Router
{

    private array $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }


    public function add(string $path, string $method, string $controller)
    {
        $this->routes[] = [
            "path" => $path,
            "method" => $method,
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

    public function dispatch(string $path)
    {
        $normalizePath = $this->normalizePath($path);

        foreach ($this->routes as $route) {
            $path = $route["path"];

            if ($path == $normalizePath && $_SERVER['REQUEST_METHOD'] == $route["method"]) {

                // we get the controller and do thing here 


                // $controller = new Controller()

            }
        }
    }

    private function match() {}
}
