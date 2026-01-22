<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Конфигурация подключения к базе данных
 * Централизованное место для получения параметров подключения
 */
class DatabaseConfig
{
    /**
     * Получить параметры подключения к БД
     * 
     * @return array Массив с параметрами: host, dbname, username, password
     */
    public static function getParams(): array
    {
        return [
            'host' => $_ENV['DB_HOST'] ?? 'mysql',
            'dbname' => $_ENV['DB_DATABASE'] ?? 'abelo_host_tz',
            'username' => $_ENV['DB_USERNAME'] ?? 'root',
            'password' => $_ENV['DB_PASSWORD'] ?? 'root'
        ];
    }

    /**
     * Получить DSN строку для PDO
     * 
     * @return string DSN строка
     */
    public static function getDsn(): string
    {
        $params = self::getParams();
        return "mysql:host={$params['host']};dbname={$params['dbname']};charset=utf8mb4";
    }
}
