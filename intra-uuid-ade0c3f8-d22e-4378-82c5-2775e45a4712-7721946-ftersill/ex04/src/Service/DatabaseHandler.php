<?php

namespace App\Service;

use Doctrine\DBAL\Connection;
use Exception;

class DatabaseHandler {

    public function __construct(private Connection $connection) {}

    public function createTable(): bool {
        try {
            $sql = "
                CREATE TABLE IF NOT EXISTS users_ex04 (
                id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                username VARCHAR(255) UNIQUE NOT NULL
                );
            ";
            $this->connection->executeStatement($sql);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function deleteTable(): bool {
        try {
            $sql = "DROP TABLE IF EXISTS users_ex04";
            $this->connection->executeStatement($sql);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function addUser(string $username): bool {
        try {
            $sql = "INSERT INTO users_ex04 (username) VALUES (:username) ON CONFLICT DO NOTHING";
            $this->connection->executeStatement($sql, ['username' => $username]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function listUsers(): array {
        try {
            $sql = "SELECT * FROM users_ex04 ORDER BY id ASC";
            return $this->connection->fetchAllAssociative($sql);
        } catch (Exception $e) {
            return [];
        }
    }

    public function getUserById(int $id): ?array {
        try {
            $sql = "SELECT * FROM users_ex04 WHERE id = :id";
            $result = $this->connection->fetchAssociative($sql, ['id' => $id]);
            return $result ?: null;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Deletes user only if condition passes (user exists in database).
     * If condition fails, delete is not executed.
     */
    public function deleteUser(int $id): ?array {
        $user = $this->getUserById($id);
        if (!$user) {
            return null; // Condition failed: user does not exist
        }

        try {
            $sql = "DELETE FROM users_ex04 WHERE id = :id";
            $this->connection->executeStatement($sql, ['id' => $id]);
            return $user;
        } catch (Exception $e) {
            return null;
        }
    }
}
