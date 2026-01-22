<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Requests\CategoryPageRequest;
use App\Services\CategoryService;
use App\Services\PaginationService;

class CategoryController extends BaseController
{
    private CategoryService $categoryService;
    private PaginationService $paginationService;

    /**
     * Конструктор контроллера страницы категории
     * 
     * @param \Smarty $smarty Объект Smarty для работы с шаблонами
     */
    public function __construct(\Smarty $smarty)
    {
        parent::__construct($smarty);
        $this->categoryService = new CategoryService();
        $this->paginationService = new PaginationService();
    }

    /**
     * Отображение страницы категории с постами
     * 
     * Получает данные категории, список постов с сортировкой и пагинацией.
     * Поддерживает GET-параметры:
     * - id (обязательный) - ID категории
     * - sort (опциональный) - тип сортировки: 'date' или 'views' (по умолчанию 'date')
     * - page (опциональный) - номер страницы пагинации (по умолчанию 1)
     * 
     * При отсутствии или невалидном ID категории отображает страницу 404.
     * 
     * @return void
     */
    public function show(): void
    {
        // Получаем разрешенные значения из конфига Smarty
        $perPageOptions = array_map(
            'intval',
            explode(',', $this->smarty->getConfigVariable('pagination_per_page_options'))
        );

        try {
            $request = new CategoryPageRequest($_GET, $perPageOptions);
        } catch (\InvalidArgumentException $e) {
            $this->show404();
            return;
        }

        // Получаем данные категории и постов
        $data = $this->categoryService->getCategoryPageData(
            $request->categoryId,
            $request->sort,
            $request->page,
            $request->perPage
        );
        
        if (!$data) {
            $this->show404();
            return;
        }

        // Формируем данные для пагинации через сервис
        $pagination = $this->paginationService->build(
            $data['currentPage'],
            $data['totalPages'],
            $request->perPage
        );

        $baseUrl = '/category';
        $queryParams = [
            'id' => $data['category']['id'],
            'sort' => $request->sort,
            'perPage' => $request->perPage,
        ];

        $this->smarty->assign([
            'category' => $data['category'],
            'posts' => $data['posts'],
            'sort' => $request->sort,
            'pagination' => $pagination,
            'baseUrl' => $baseUrl,
            'queryParams' => $queryParams,
            'perPageOptions' => $perPageOptions,
            'title' => $data['category']['name']
        ]);

        $this->smarty->display('pages/category.tpl');
    }
}
