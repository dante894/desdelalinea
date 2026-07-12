<?php

namespace App\Core;

use PDO;

class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $port = $_ENV['DB_PORT'] ?? '3306';

            self::$instance = new PDO(
                "mysql:host=" . $_ENV['DB_HOST'] .
                ";port=" . $port .
                ";dbname=" . $_ENV['DB_NAME'] .
                ";charset=utf8mb4",
                $_ENV['DB_USER'],
                $_ENV['DB_PASS'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]
            );
        }

        return self::$instance;
    }
}
