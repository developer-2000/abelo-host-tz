<?php

declare(strict_types=1);

namespace App\Http\Requests;

/**
 * DTO для параметров запроса страницы категории
 */
class CategoryPageRequest
{
    public int $categoryId;
    public string $sort;
    public int $page;
    public int $perPage;

    /**
     * Конструктор запроса страницы категории
     * 
     * @param array $query GET-параметры запроса
     * @param array $perPageOptions Разрешенные значения для perPage
     * @throws \InvalidArgumentException При невалидном categoryId
     */
    public function __construct(array $query, array $perPageOptions)
    {
        $this->categoryId = (int)($query['id'] ?? 0);
        if ($this->categoryId <= 0) {
            throw new \InvalidArgumentException('Invalid category id');
        }

        $this->sort = in_array($query['sort'] ?? 'date', ['date', 'views'], true)
            ? $query['sort']
            : 'date';

        $this->page = max(1, (int)($query['page'] ?? 1));

        $defaultPerPage = $perPageOptions[0] ?? 3;
        $perPage = (int)($query['perPage'] ?? $defaultPerPage);
        $this->perPage = in_array($perPage, $perPageOptions, true)
            ? $perPage
            : $defaultPerPage;
    }
}
