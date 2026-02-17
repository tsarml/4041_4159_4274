<?php


function getDB(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $host   = $_ENV['DB_HOST'] ?? 'localhost';
        $dbname = $_ENV['DB_NAME'] ?? '4041_4159_4274';
        $user   = $_ENV['DB_USER'] ?? 'root';
        $pass   = $_ENV['DB_PASS'] ?? '';

        try {
            $pdo = new PDO(
                "mysql:host={$host};dbname={$dbname};charset=utf8",
                $user,
                $pass,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        } catch (PDOException $e) {
            die(json_encode([
                'error' => 'Connexion base de données impossible : ' . $e->getMessage()
            ]));
        }
    }

    return $pdo;
}