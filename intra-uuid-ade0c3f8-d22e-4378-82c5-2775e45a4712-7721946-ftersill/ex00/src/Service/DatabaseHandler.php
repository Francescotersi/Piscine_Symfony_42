<?php

namespace App\Service;

use Doctrine\DBAL\Connection;
use Exception;

class DatabaseHandler {

    public function __construct(private Connection $connection) {}

    public function checkTableExists(string $tableName = 'users_data_ex00'): bool {
        try {
            $schemaManager = $this->connection->createSchemaManager();
            return $schemaManager->tablesExist([$tableName]);
        } catch (Exception $e) {
            try {
                $sql = "SELECT 1 FROM information_schema.tables WHERE table_name = :tableName";
                $result = $this->connection->fetchOne($sql, ['tableName' => $tableName]);
                return !empty($result);
            } catch (Exception $ex) {
                return false;
            }
        }
    }

    public function createTable(): array {
        try {
            if ($this->checkTableExists('users_data_ex00')) {
                return [
                    'status' => 'success',
                    'message' => 'Notice: Table "users_data" already exists in database.'
                ];
            }

            $sql = "
                CREATE TABLE IF NOT EXISTS users_data_ex00 (
                id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                username VARCHAR(255) UNIQUE NOT NULL,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) UNIQUE NOT NULL,
                enable BOOLEAN NOT NULL,
                birthdate TIMESTAMP NULL,
                address TEXT
                );
            ";
            $this->connection->executeStatement($sql);
            return [
                'status' => 'success',
                'message' => 'Success: Table "users_data" created successfully'
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error: Table not created: ' . $e->getMessage()
            ];
        }
    }

    public function dropTable(): array {
        try {
            if (!$this->checkTableExists('users_data_ex00')) {
                return [
                    'status' => 'success',
                    'message' => 'Notice: Table "users_data" does not exist in database.'
                ];
            }

            $this->connection->executeStatement('DROP TABLE IF EXISTS users_data_ex00');
            return [
                'status' => 'success',
                'message' => 'Success: Table "users_data" deleted successfully'
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error: Table not deleted: ' . $e->getMessage()
            ];
        }
    }
}
