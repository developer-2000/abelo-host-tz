<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    private Category $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new Category();
    }

    /**
     * Получить категории с постами для главной страницы
     * 
     * @param int $limit Количество постов на категорию
     * @return array Массив категорий с постами
     */
    public function getCategoriesWithPosts(int $limit = 3): array
    {
        return $this->categoryModel->getAllWithPosts($limit);
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
        $category = $this->categoryModel->getById($categoryId);
        
        if (!$category) {
            return null;
        }

        // Валидация сортировки
        if (!in_array($sort, ['date', 'views'])) {
            $sort = 'date';
        }

        // Валидация страницы
        $page = max(1, $page);

        $posts = $this->categoryModel->getPosts($categoryId, $sort, $page, $perPage);
        $totalPosts = $this->categoryModel->countPosts($categoryId);
        $totalPages = (int)ceil($totalPosts / $perPage);

        return [
            'category' => $category,
            'posts' => $posts,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'sort' => $sort
        ];
    }

}
