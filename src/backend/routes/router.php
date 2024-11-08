<?php

class Router
{
    private $routes = [];

    public function add($method, $path, $callback)
    {
        $path = preg_replace('/\{(\w+)\}/', '(\d+)', $path);
        $this->routes[] = [
            'method' => $method,
            'path' => "#^" . $path . "$#",
            'callback' => $callback
        ];
    }

    public function dispatch($requestedPath)
    {
        $requestedMethod = $_SERVER["REQUEST_METHOD"];
        $requestedPath = rtrim(ltrim($requestedPath, '/'), '/');

        foreach ($this->routes as $route) {
            if ($route['method'] === $requestedMethod && preg_match($route['path'], '/' . $requestedPath, $matches)) {
                array_shift($matches);
                return call_user_func($route['callback'], ...$matches);
            }
        }

        echo json_encode(["error" => "404 - Página não encontrada"]);
    }
}
