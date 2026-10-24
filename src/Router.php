<?php

namespace App;

class Router
{
    private array $routes = [];

    public function get(string $path, callable|array $action): void
    {
        $this->addRoute('GET', $path, $action);
    }

    public function post(string $path, callable|array $action): void
    {
        $this->addRoute('POST', $path, $action);
    }

    public function any(string $path, callable|array $action): void
    {
        $this->addRoute('GET', $path, $action);
        $this->addRoute('POST', $path, $action);
    }

    private function addRoute(string $method, string $path, callable|array $action): void
    {
        $cleanPath = '/' . trim($path, '/');
        if ($cleanPath === '//') {
            $cleanPath = '/';
        }
        $this->routes[$method][$cleanPath] = $action;

        // Also register with .php suffix for backward compatibility
        if ($cleanPath !== '/' && !str_ends_with($cleanPath, '.php')) {
            $this->routes[$method][$cleanPath . '.php'] = $action;
        }
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $uri = is_string($uri) ? $uri : '/';

        $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
        $basePath = rtrim($basePath, '/');
        if ($basePath !== '' && ($uri === $basePath || str_starts_with($uri, $basePath . '/'))) {
            $uri = substr($uri, strlen($basePath));
        }

        $uri = '/' . trim($uri, '/');
        if ($uri === '//') {
            $uri = '/';
        }

        // Bypasses admin requests to native admin directory
        if (str_starts_with($uri, '/admin')) {
            return;
        }

        if (isset($this->routes[$method][$uri])) {
            $action = $this->routes[$method][$uri];
            $this->execute($action);
            return;
        }

        // Check fallback for index.php
        if ($uri === '/index.php' && isset($this->routes[$method]['/'])) {
            $this->execute($this->routes[$method]['/']);
            return;
        }

        // 404 handler
        http_response_code(404);
        view('404', ['pageTitle' => '404 - Page Not Found']);
    }

    private function execute(callable|array $action): void
    {
        if (is_callable($action)) {
            call_user_func($action);
            return;
        }

        if (is_array($action) && count($action) === 2) {
            [$class, $method] = $action;
            $controller = new $class();
            $controller->$method();
        }
    }
}
