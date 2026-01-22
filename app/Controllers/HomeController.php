<?php

declare(strict_types=1);

namespace App\Controllers;

use Smarty;

class HomeController
{
    private Smarty $smarty;

    public function __construct(Smarty $smarty)
    {
        $this->smarty = $smarty;
    }

    public function index(): void
    {
        $this->smarty->assign('title', 'Главная');
        $this->smarty->display('pages/home.tpl');
    }
}
