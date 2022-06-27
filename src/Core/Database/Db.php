<?php

namespace App\Core\Database;

use PDO;
use PDOException;

class Db
{
    private static $pdo = null;

    public static function getInstance()
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        try {
            return self::$pdo = new PDO(
                sprintf('mysql:host=%s;dbname=%s;charset=utf8', DB_HOST, DB_NAME),
                DB_USERNAME,
                DB_PASSWORD,
                [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_CLASS]
            );
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }
}