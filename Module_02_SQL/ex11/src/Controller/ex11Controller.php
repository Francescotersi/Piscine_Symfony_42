<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\DBAL\Connection;


class ex11Controller extends AbstractController {
    public function __construct(private Connection $connection) {}

    #[Route(path:'/new', name:'ex11_newTable')]
    public function newTable(): Response {
        try {
            $sql = '                
                CREATE TABLE IF NOT EXISTS person (
                id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                username VARCHAR(255) NOT NULL,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL);
                
                CREATE TABLE IF NOT EXISTS bank_accounts (
                id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                money INTEGER NOT NULL,
                owner_id INTEGER NOT NULL UNIQUE,
                FOREIGN KEY (owner_id) REFERENCES person(id)
                ON DELETE CASCADE
                );
            ';

            $this->connection->executeStatement($sql);
            $this->addFlash('success', 'tables has been created');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Error while creating tables: ' . $e->getMessage());
        }
        return new Response("Success: new table created");
    }

    #[Route(path:"/list", name:"ex11_listTable", methods:["GET"])]
    public function listTable(Request $request): Response {
        try {
            $allowedSortColumns = ['p.name', 'p.username', 'b.money'];
            $sort = $request->query->get('sort', 'p.name');
            if (!in_array($sort, $allowedSortColumns)) {
                $sort = 'p.name'; 
            }

            $order = strtoupper($request->query->get('order', 'ASC'));
            if (!in_array($order, ['ASC', 'DESC'])) {
                $order = 'ASC';
            }
            $nameFilter = $request->query->get('name');
            $minMoneyFilter = $request->query->get('min_money');
            $sql = '
                SELECT p.id, p.username, p.name, p.email, b.money
                FROM person p
                JOIN bank_accounts b ON p.id = b.owner_id
                WHERE 1=1
            ';
            $params = [];
            if (!empty($nameFilter)) {
                $sql .= ' AND p.name LIKE :name';
                $params['name'] = '%' . $nameFilter . '%';
            }
            if (is_numeric($minMoneyFilter)) {
                $sql .= ' AND b.money >= :min_money';
                $params['min_money'] = (int) $minMoneyFilter;
            }
            $sql .= sprintf(' ORDER BY %s %s', $sort, $order);
            $results = $this->connection->fetchAllAssociative($sql, $params);

            return $this->render('listTable.html.twig', [
                'accounts' => $results,
            ]);
        } catch (\Exception $e) {
            return new Response("Error while ordering: " . $e->getMessage());
        }
    }

    #[Route(path:'/seed', name:'ex11_seedTable')]
    public function seedTable(): Response {
        try {
            $users = [
                ['mario99', 'Mario Rossi', 'mario@example.com', 1500],
                ['luigi_bros', 'Luigi Verdi', 'luigi@example.com', 800],
                ['peach_p', 'Princess Peach', 'peach@example.com', 12000],
                ['bowser_king', 'Bowser Koopa', 'bowser@example.com', 50000],
                ['toad_x', 'Toad Mushroom', 'toad@example.com', 200],
                ['yoshi_d', 'Yoshi Dino', 'yoshi@example.com', 3500],
                ['wario_w', 'Wario Ware', 'wario@example.com', 8500],
            ];

            foreach ($users as $u) {
                $result = $this->connection->executeQuery(
                    'INSERT INTO person (username, name, email) VALUES (:u, :n, :e) RETURNING id',
                    ['u' => $u[0], 'n' => $u[1], 'e' => $u[2]]
                );
                $personId = $result->fetchOne();
                $this->connection->executeStatement(
                    'INSERT INTO bank_accounts (money, owner_id) VALUES (:m, :o)',
                    ['m' => $u[3], 'o' => $personId]
                );
            }
            $this->addFlash('success', count($users) . ' utenti fittizi inseriti con successo!');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Errore durante il seed: ' . $e->getMessage());
        }
        return $this->redirectToRoute('ex11_listTable');
    }
}