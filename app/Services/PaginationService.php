<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Сервис для работы с пагинацией
 */
class PaginationService
{
    /**
     * Построение данных для пагинации
     * 
     * @param int $currentPage Текущая страница
     * @param int $totalPages Общее количество страниц
     * @param int $perPage Количество элементов на странице
     * @return array Массив с данными пагинации
     */
    public function build(int $currentPage, int $totalPages, int $perPage): array
    {
        return [
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'startPage' => max(1, $currentPage - 2),
            'endPage' => min($totalPages, $currentPage + 2),
            'perPage' => $perPage,
        ];
    }
}
