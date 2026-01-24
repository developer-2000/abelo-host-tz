<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $params = DatabaseConfig::getParams();

            try {
                self::$connection = new PDO(
                    DatabaseConfig::getDsn(),
                    $params['username'],
                    $params['password'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );
            } catch (PDOException $e) {
                throw new \RuntimeException('Ошибка подключения к базе данных.');
            }
        }

        return self::$connection;
    }
}
