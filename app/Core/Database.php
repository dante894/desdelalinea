<?php

namespace App\Core;

use PDO;

class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if(self::$instance === null){

<<<<<<< HEAD
            $port = $_ENV['DB_PORT'] ?? '3306';

=======
>>>>>>> cb116038913c643d0c8c68dd276ebb93c78d7470
            self::$instance =
            new PDO(

                "mysql:host="
                .$_ENV['DB_HOST'].

<<<<<<< HEAD
                ";port="
                .$port.

=======
>>>>>>> cb116038913c643d0c8c68dd276ebb93c78d7470
                ";dbname="
                .$_ENV['DB_NAME'].

                ";charset=utf8mb4",

                $_ENV['DB_USER'],

<<<<<<< HEAD
                $_ENV['DB_PASS'],

                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]
=======
                $_ENV['DB_PASS']
>>>>>>> cb116038913c643d0c8c68dd276ebb93c78d7470
            );

            self::$instance->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
        }

        return self::$instance;
    }
}