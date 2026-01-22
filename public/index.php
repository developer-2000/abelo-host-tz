<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../app/Core/Router.php';

$smarty = require_once __DIR__ . '/../app/Core/SmartyInit.php';

$router = new Router($smarty);
$router->dispatch();
