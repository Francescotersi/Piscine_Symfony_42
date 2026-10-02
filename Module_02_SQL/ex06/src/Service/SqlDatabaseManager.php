<?php

namespace App\Service;

use Doctrine\DBAL\Connection;

class SqlDatabaseManager {

    public function __construct(private Connection $connection) {}

    public function createTable(): void {
        $sql = "
            CREATE TABLE IF NOT EXISTS users_data (
            id INTEGER  GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
            username VARCHAR(255) NOT NULL,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            enable BOOLEAN NOT NULL,
            birthdate TIMESTAMP NULL,
            address TEXT
            );
        ";
        $this->connection->executeStatement($sql);
    }

    public function dropTable(): void {
        $sql = "DROP TABLE IF EXISTS users_data";
        $this->connection->executeStatement($sql);
    }

    public function addUser(array $data): void {
        $sql = "INSERT INTO users_data (username, name, email, enable, birthdate, address)
                VALUES (:username, :name, :email, :enable, :birthdate, :address)";

        $this->connection->executeStatement($sql, [
            'username' => $data['username'],
            'name' => $data['name'],
            'email' => $data['email'],
            'enable' => $data['enable'] ? 'true' : 'false',
            'birthdate' => $data['birthdate'],
            'address' => $data['address'],
        ]);
    }

    public function getAllUsers(): array {
        $sql = "SELECT * FROM users_data";
        return $this->connection->fetchAllAssociative($sql);
    }

    public function getUserById(int $id): ?array {
        $sqlSelect = 'SELECT * FROM users_data WHERE id = :id';
        $user = $this->connection->fetchAssociative($sqlSelect, ['id' => $id]);
        return $user ?: null;
    }

    public function deleteUserById(int $id): void {
        $sqlDelete = 'DELETE FROM users_data WHERE id = :id';
        $this->connection->executeStatement($sqlDelete, ['id' => $id]);
    }

    public function updateUser(int $id, array $data): void {
        $sql = "UPDATE users_data
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
            'birthdate' => $data['birthdate'],
            'address' => $data['address'],
        ]);
    }
}
