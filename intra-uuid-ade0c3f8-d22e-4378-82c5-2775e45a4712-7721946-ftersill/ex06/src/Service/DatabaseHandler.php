<?php

namespace App\Service;

use Doctrine\DBAL\Connection;
use Exception;

class DatabaseHandler {

    public function __construct(private Connection $connection) {}

    public function createTable(): bool {
        try {
            $sql = "
                CREATE TABLE IF NOT EXISTS users_data_ex06 (
                id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                username VARCHAR(255) NOT NULL,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                enable BOOLEAN NOT NULL,
                birthdate TIMESTAMP NULL,
                address TEXT
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
            $sql = "DROP TABLE IF EXISTS users_data_ex06";
            $this->connection->executeStatement($sql);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function addUser(array $data): bool {
        try {
            $birthdate = $data['birthdate']?->format('Y-m-d H:i:s');
            $sql = "INSERT INTO users_data_ex06 (username, name, email, enable, birthdate, address)
                    VALUES (:username, :name, :email, :enable, :birthdate, :address)";

            $this->connection->executeStatement($sql, [
                'username' => $data['username'],
                'name' => $data['name'],
                'email' => $data['email'],
                'enable' => $data['enable'] ? 'true' : 'false',
                'birthdate' => $birthdate,
                'address' => $data['address'],
            ]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function listUsers(): array {
        try {
            $sql = "SELECT * FROM users_data_ex06 ORDER BY id ASC";
            return $this->connection->fetchAllAssociative($sql);
        } catch (Exception $e) {
            return [];
        }
    }

    public function getUserById(int $id): ?array {
        try {
            $sql = "SELECT * FROM users_data_ex06 WHERE id = :id";
            $result = $this->connection->fetchAssociative($sql, ['id' => $id]);
            return $result ?: null;
        } catch (Exception $e) {
            return null;
        }
    }

    public function deleteUser(int $id): ?array {
        $user = $this->getUserById($id);
        if (!$user) {
            return null;
        }

        try {
            $sql = "DELETE FROM users_data_ex06 WHERE id = :id";
            $this->connection->executeStatement($sql, ['id' => $id]);
            return $user;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Executes the SQL UPDATE query outside of the controller.
     */
    public function updateUser(int $id, array $data): bool {
        try {
            $birthdate = $data['birthdate']?->format('Y-m-d H:i:s');
            $sql = "UPDATE users_data_ex06
                    SET username = :username,
                        name = :name,
                        email = :email,
                        enable = :enable,
                        birthdate = :birthdate,
                        address = :address
                    WHERE id = :id";

            $this->connection->executeStatement($sql, [
                'id' => $id,
                'username' => $data['username'],
                'name' => $data['name'],
                'email' => $data['email'],
                'enable' => $data['enable'] ? 'true' : 'false',
                'birthdate' => $birthdate,
                'address' => $data['address'],
            ]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
