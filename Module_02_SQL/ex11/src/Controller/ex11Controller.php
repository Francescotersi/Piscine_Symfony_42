<?php

namespace App\Controller;

use App\Service\SqlDatabaseManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ex11Controller extends AbstractController
{
    public function __construct(private SqlDatabaseManager $dbManager)
    {
    }

    #[Route(path: '/new', name: 'ex11_newTable')]
    public function newTable(): Response
    {
        try {
            $this->dbManager->createTables();
            $this->addFlash('success', 'tables has been created');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Error while creating tables: ' . $e->getMessage());
        }
        return $this->redirectToRoute('ex11_listTable');
    }

    #[Route(path: "/list", name: "ex11_listTable", methods: ["GET"])]
    public function listTable(Request $request): Response
    {
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
            $rawMinMoney = $request->query->get('min_money');
            $minMoneyFilter = is_numeric($rawMinMoney) ? (int) $rawMinMoney : null;

            $results = $this->dbManager->findAccountsWithFilterAndSort($nameFilter, $minMoneyFilter, $sort, $order);

            return $this->render('listTable.html.twig', [
                'accounts' => $results,
            ]);
        } catch (\Exception $e) {
            $this->addFlash('error', 'Error while ordering: ' . $e->getMessage());
            return $this->render('listTable.html.twig', [
                'accounts' => [],
            ]);
        }
    }

    #[Route(path: '/seed', name: 'ex11_seedTable')]
    public function seedTable(): Response
    {
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

            $this->dbManager->seedUsers($users);
            $this->addFlash('success', count($users) . ' utenti fittizi inseriti con successo!');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Errore durante il seed: ' . $e->getMessage());
        }
        return $this->redirectToRoute('ex11_listTable');
    }
}