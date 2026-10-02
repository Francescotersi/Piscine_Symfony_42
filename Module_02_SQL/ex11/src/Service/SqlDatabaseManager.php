<?php

namespace App\Service;

use Doctrine\DBAL\Connection;

class SqlDatabaseManager {

    public function __construct(private Connection $connection) {}

    public function createTables(): void {
        $sql = '                
            CREATE TABLE IF NOT EXISTS persons (
            id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
            username VARCHAR(255) NOT NULL,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL);
            
            CREATE TABLE IF NOT EXISTS bank_accounts (
            id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
            money INTEGER NOT NULL,
            owner_id INTEGER NOT NULL UNIQUE,
            FOREIGN KEY (owner_id) REFERENCES persons(id)
            ON DELETE CASCADE
            );
        ';

        $this->connection->executeStatement($sql);
    }

    public function findAccountsWithFilterAndSort(?string $nameFilter, ?int $minMoneyFilter, string $sort, string $order): array {
        $sql = '
            SELECT p.id, p.username, p.name, p.email, b.money
            FROM persons p
            JOIN bank_accounts b ON p.id = b.owner_id
            WHERE 1=1
        ';
        $params = [];
        if (!empty($nameFilter)) {
            $sql .= ' AND p.name LIKE :name';
            $params['name'] = '%' . $nameFilter . '%';
        }
        if ($minMoneyFilter !== null) {
            $sql .= ' AND b.money >= :min_money';
            $params['min_money'] = $minMoneyFilter;
        }
        $sql .= sprintf(' ORDER BY %s %s', $sort, $order);
        return $this->connection->fetchAllAssociative($sql, $params);
    }

    public function seedUsers(array $users): void {
        foreach ($users as $u) {
            $result = $this->connection->executeQuery(
                'INSERT INTO persons (username, name, email) VALUES (:u, :n, :e) RETURNING id',
                ['u' => $u[0], 'n' => $u[1], 'e' => $u[2]]
            );
            $personId = $result->fetchOne();
            $this->connection->executeStatement(
                'INSERT INTO bank_accounts (money, owner_id) VALUES (:m, :o)',
                ['m' => $u[3], 'o' => $personId]
            );
        }
    }
}
