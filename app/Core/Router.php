<?php

declare(strict_types=1);

namespace App\Core;

use Smarty;

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
            $this->smarty->assign('title', '404 - Страница не найдена');
            $this->smarty->display('pages/404.tpl');
            return;
        }

        [$controller, $method] = $this->routes[$uri];

        (new $controller($this->smarty))->$method();
    }
}
