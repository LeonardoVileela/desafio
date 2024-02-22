<?php

namespace Infrastructure\Persistence;

use PDO;

class DatabaseConnection
{
    public static function getConnection(): PDO
    {
        /* $host = '127.0.0.1';
        $db = 'sicredi';
        $user = 'root';
        $pass = '';
        $charset = 'utf8mb4';*/

        $host = getenv("HOST");
        $db = getenv("DB");
        $user = getenv("USER_NAME");
        $pass = getenv("PASS_NAME");
        $charset = 'utf8mb4';


        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            return new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
}
