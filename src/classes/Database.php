<?php

declare(strict_types=1);

namespace App;

use PDO;

class Database {
    public static function getConnection(): PDO {
        $options = [
            // где табы аааааа
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
        $dsn = 'pgsql:host=db;port=5432;dbname=minibank';
        $user = 'root';
        $pass = 'secret_password';
        $pdo = new PDO($dsn, $user, $pass, $options);
        return $pdo;
    }
}