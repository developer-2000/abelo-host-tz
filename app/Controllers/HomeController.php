<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\CategoryService;

class HomeController extends BaseController
{
    private CategoryService $categoryService;

    /**
     * @param \Smarty $smarty Объект Smarty для работы с шаблонами
     */
    public function __construct(\Smarty $smarty)
    {
        parent::__construct($smarty);
        $this->categoryService = new CategoryService();
    }

    /**
     * Отображение главной страницы блога
     * Получает категории с последними 3 постами и передает их в шаблон.
     *
     * @return void
     */
    public function index(): void
    {
        $categories = $this->categoryService->getCategoriesWithPosts(3);

        $this->smarty->assign('categories', $categories);
        $this->smarty->assign('title', 'Главная');
        $this->smarty->display('pages/home.tpl');
    }
}
