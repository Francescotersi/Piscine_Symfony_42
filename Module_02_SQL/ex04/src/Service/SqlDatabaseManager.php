<?php

namespace App\Service;

use Doctrine\DBAL\Connection;

class SqlDatabaseManager {

    public function __construct(private Connection $connection) {}

    public function createTable(): void {
        $sql = "
            CREATE TABLE IF NOT EXISTS users (
            id INTEGER  GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
            username VARCHAR(255) UNIQUE NOT NULL
            );
        ";
        $this->connection->executeStatement($sql);
    }

    public function dropTable(): void {
        $sql = "DROP TABLE IF EXISTS users";
        $this->connection->executeStatement($sql);
    }

    public function addUser(string $username): void {
        $sql = "INSERT INTO users (username)
                VALUES (:username)
                ON CONFLICT DO NOTHING";
        $this->connection->executeStatement($sql, ['username' => $username]);
    }

    public function getAllUsers(): array {
        $sql = "SELECT * FROM users";
        return $this->connection->fetchAllAssociative($sql);
    }

    public function getUserById(int $id): ?array {
        $sqlSelect = 'SELECT * FROM users WHERE id = :id';
        $user = $this->connection->fetchAssociative($sqlSelect, ['id' => $id]);
        return $user ?: null;
    }

    public function deleteUserById(int $id): void {
        $sqlDelete = 'DELETE FROM users WHERE id = :id';
        $this->connection->executeStatement($sqlDelete, ['id' => $id]);
    }
}
