<?php

declare(strict_types=1);

namespace App\Helpers;

/**
 * Хелпер для генерации ссылок пагинации
 */
class PaginationHelper
{
    /**
     * Генерация ссылки для страницы пагинации
     * 
     * @param array $queryParams Массив параметров запроса
     * @param string $baseUrl Базовый URL
     * @param int $page Номер страницы
     * @return string Сгенерированная ссылка
     */
    public static function buildPageLink(array $queryParams, string $baseUrl, int $page): string
    {
        $queryParams['page'] = $page;
        return $baseUrl . '?' . http_build_query($queryParams);
    }
}
