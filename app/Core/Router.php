<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $uri, string $action): void
    {
        $this->routes['GET'][$uri] = $action;
    }

    public function post(string $uri, string $action): void
    {
        $this->routes['POST'][$uri] = $action;
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (isset($this->routes[$method][$uri])) {
            [$controller, $func] = explode('@', $this->routes[$method][$uri]);
            $class = "App\\Controllers\\{$controller}";
            (new $class())->$func();
            return;
        }

        http_response_code(404);
        echo '<h1 style="font-family:sans-serif;color:#fff;background:#0a0f0a;padding:3rem;margin:0">404 — Página no encontrada</h1>';
    }
}
