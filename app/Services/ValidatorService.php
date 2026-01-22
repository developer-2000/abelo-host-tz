<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Сервис для валидации входных данных
 */
class ValidatorService
{
    /**
     * Валидация ID с возвратом null вместо исключения
     * 
     * Проверяет, что переданный параметр является валидным положительным числом.
     * Возвращает null если ID невалиден (для более мягкой обработки).
     * 
     * @param mixed $param Параметр для валидации (обычно из $_GET)
     * @return int|null Валидный ID или null если невалиден
     */
    public function validateIdOrNull(mixed $param): ?int
    {
        $id = (int)($param ?? 0);
        
        return $id > 0 ? $id : null;
    }
}
