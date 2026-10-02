<?php

namespace App\Service;

use Doctrine\DBAL\Connection;

class SqlDatabaseManager {

    public function __construct(private Connection $connection) {}

    public function createPersonsTable(): void {
        $sql = "
            CREATE TABLE IF NOT EXISTS persons (
            id INTEGER  GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
            username VARCHAR(255) NOT NULL,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            enable BOOLEAN NOT NULL,
            birthdate TIMESTAMP NULL
            );
        ";
        $this->connection->executeStatement($sql);
    }

    public function addMaritalStatusColumn(): void {
        $sql = "
            ALTER TABLE persons
            ADD COLUMN IF NOT EXISTS marital_status VARCHAR(20) 
            CHECK (marital_status IN ('single', 'married', 'widower')
            );
        ";
        $this->connection->executeStatement($sql);
    }

    public function createRelatedTables(): void {
        $sql = "
            CREATE TABLE IF NOT EXISTS bank_accounts (
            id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
            money INTEGER NOT NULL,
            owner_id INTEGER NOT NULL UNIQUE,
            FOREIGN KEY (owner_id) REFERENCES persons(id)
            ON DELETE CASCADE
            );

            CREATE TABLE IF NOT EXISTS addresses (
            id INTEGER  GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
            road VARCHAR(255) NOT NULL,
            owner_id INT,
            FOREIGN KEY (owner_id) REFERENCES persons(id)
            );
        ";
        $this->connection->executeStatement($sql);
    }
}
