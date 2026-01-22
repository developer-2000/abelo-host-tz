<?php

use App\Controllers\CategoryController;
use App\Controllers\HomeController;
use App\Controllers\PostController;

return [
    '/' => [HomeController::class, 'index'],
    '/category' => [CategoryController::class, 'show'],
    '/post' => [PostController::class, 'show'],
];
