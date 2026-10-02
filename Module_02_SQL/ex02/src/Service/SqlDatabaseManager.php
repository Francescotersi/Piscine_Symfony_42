<?php

namespace App\Service;

use Doctrine\DBAL\Connection;

class SqlDatabaseManager {

    public function __construct(private Connection $connection) {}

    public function createTable(): void {
        $sql = "
            CREATE TABLE IF NOT EXISTS users (
            id INTEGER  GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
            username VARCHAR(255) UNIQUE NOT NULL,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            enable BOOLEAN NOT NULL,
            birthdate TIMESTAMP NULL,
            address TEXT
            );
        ";
        $this->connection->executeStatement($sql);
    }

    public function insertUser(array $data): void {
        $sql = "INSERT INTO users (username, name, email, enable, birthdate, address)
                VALUES (:username, :name, :email, :enable, :birthdate, :address)
                ON CONFLICT DO NOTHING";

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
        $sql = "SELECT * FROM users";
        return $this->connection->fetchAllAssociative($sql);
    }

    public function dropTable(): void {
        $sql = 'DROP TABLE IF EXISTS users';
        $this->connection->executeStatement($sql);
    }
}
