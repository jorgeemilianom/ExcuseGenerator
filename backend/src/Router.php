<?php

declare(strict_types=1);

class Router
{
    private array $routes = [];

    public function post(string $path, array $handler): void
    {
        $this->routes[] = ['method' => 'POST', 'path' => $path, 'handler' => $handler];
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $uri) {
                [$class, $action] = $route['handler'];
                $controller = new $class();
                $controller->$action();
                return;
            }
        }

        http_response_code(404);
        echo json_encode(['error' => 'not_found']);
    }
}
