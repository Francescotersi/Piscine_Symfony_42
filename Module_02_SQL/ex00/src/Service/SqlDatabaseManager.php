<?php

namespace App\Service;

use Doctrine\DBAL\Connection;

class SqlDatabaseManager {

    public function __construct(private Connection $connection) {}

    public function createTable(): void {
        $sql = "
            CREATE TABLE IF NOT EXISTS users_data (
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

    public function dropTable(): void {
        $this->connection->executeStatement('DROP TABLE IF EXISTS users_data');
    }
}
