<?php

namespace App\Service;

use Doctrine\DBAL\Connection;

class SqlDatabaseManager
{
    public function __construct(private Connection $connection)
    {
    }

    public function createTable(): void
    {
        $sql = 'CREATE TABLE IF NOT EXISTS "SQL_table" (
            id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
            username VARCHAR(255) NOT NULL
        )';
        $this->connection->executeStatement($sql);
    }

    public function insertUser(string $username): void
    {
        $this->connection->executeStatement(
            'INSERT INTO "SQL_table" (username) VALUES (:username)',
            ['username' => $username]
        );
    }

    public function getAllUsers(): array
    {
        return $this->connection->fetchAllAssociative('SELECT * FROM "SQL_table" ORDER BY id ASC');
    }
}
