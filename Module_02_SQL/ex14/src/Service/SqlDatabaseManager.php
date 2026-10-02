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
        $sql = 'CREATE TABLE IF NOT EXISTS generic (
                id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                username VARCHAR(255) NOT NULL);';
        $this->connection->executeStatement($sql);
    }

    public function dropTable(): void
    {
        $this->connection->executeStatement('DROP TABLE IF EXISTS generic CASCADE;');
    }

    public function seedUsers(int $count): void
    {
        $names = ['goofy', 'mickey', 'donald', 'daisy', 'minnie', 'pluto', 'chip', 'dale', 'poo', 'piglet'];
        $this->connection->beginTransaction();
        try {
            for ($i = 0; $i < $count; $i++) {
                $base = $names[array_rand($names)];
                $username = $base . '_' . bin2hex(random_bytes(4));
                $this->connection->executeStatement(
                    'INSERT INTO generic (username) VALUES (:u)',
                    ['u' => $username]
                );
            }
            $this->connection->commit();
        } catch (\Exception $e) {
            if ($this->connection->isTransactionActive()) {
                $this->connection->rollBack();
            }
            throw $e;
        }
    }

    public function getAllUsers(): array
    {
        return $this->connection->fetchAllAssociative('SELECT * FROM generic ORDER BY id ASC');
    }

    public function insertUser(string $username): void
    {
        $sql = "INSERT INTO generic (username) VALUES ('" . $username . "')";
        $this->connection->executeStatement($sql);
    }
}
