<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\CategoryRepository;

class CategoryService
{
    private CategoryRepository $categoryRepository;

    public function __construct()
    {
        $this->categoryRepository = new CategoryRepository();
    }

    /**
     * Получить категории с постами для главной страницы
     * 
     * @param int $limit Количество постов на категорию
     * @return array Массив категорий с постами
     */
    public function getCategoriesWithPosts(int $limit = 3): array
    {
        return $this->categoryRepository->getAllWithPosts($limit);
    }

    /**
     * Получить данные категории с постами для страницы категории
     * 
     * @param int $categoryId ID категории
     * @param string $sort Тип сортировки: 'date' или 'views'
     * @param int $page Номер страницы
     * @param int $perPage Количество постов на странице
     * @return array|null Массив с данными категории, постами и пагинацией, или null если категория не найдена
     */
    public function getCategoryPageData(int $categoryId, string $sort, int $page, int $perPage): ?array
    {
        $category = $this->categoryRepository->getById($categoryId);
        
        if (!$category) {
            return null;
        }

        // Валидация сортировки
        if (!in_array($sort, ['date', 'views'])) {
            $sort = 'date';
        }

        // Валидация страницы
        $page = max(1, $page);

        $posts = $this->categoryRepository->getPosts($categoryId, $sort, $page, $perPage);
        $totalPosts = $this->categoryRepository->countPosts($categoryId);
        $totalPages = (int)ceil($totalPosts / $perPage);

        return [
            'category' => $category->toArray(),
            'posts' => $posts,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'sort' => $sort
        ];
    }

}
