<?php

declare(strict_types=1);

class Router
{
    private array $routes;
    private Smarty $smarty;

    public function __construct(Smarty $smarty)
    {
        $this->smarty = $smarty;
        $this->routes = require __DIR__ . '/../routes/web.php';
    }

    public function dispatch(): void
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = rtrim($uri, '/') ?: '/';

        if (!isset($this->routes[$uri])) {
            http_response_code(404);
            echo '404 Not Found';
            return;
        }

        [$controller, $method] = $this->routes[$uri];

        (new $controller($this->smarty))->$method();
    }
}
