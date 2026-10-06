<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\bankAccountEntity;
use App\Entity\personEntity;
use App\Repository\personRepository;

class ex12Controller extends AbstractController
{
    #[Route(path: '/new', name: 'ex12_newTable')]
    public function newTable(personRepository $personRepository): Response
    {
        try {
            $personRepository->createTables();
            $this->addFlash('success', 'Successfully created two tables');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Error while creating two tables: ' . $e->getMessage());
        }
        return $this->redirectToRoute('ex12_listTable');
    }

    #[Route(path: "/create", name: "ex12_createPerson")]
    public function createPerson(personRepository $personRepository): Response
    {
        $person = new personEntity();
        $newBankAccount = new bankAccountEntity();
        $uniq = uniqid();
        $person->setUsername("user_" . $uniq);
        $person->setName("Name " . $uniq);
        $person->setEmail("email_" . $uniq . "@test.com");
        $newBankAccount->setBalance(rand(0, 100000));
        $person->setBankAccount($newBankAccount);

        $personRepository->save($person);

        $this->addFlash('success', 'Created PersonEntity with ID: ' . $person->getId());
        return $this->redirectToRoute('ex12_listTable');
    }

    #[Route(path: '/seed', name: 'ex12_seedTable')]
    public function seedTable(personRepository $personRepository): Response
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

            $personRepository->seed($users);
            $this->addFlash('success', 'Fake accounts created');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Errore durante il seed: ' . $e->getMessage());
        }
        return $this->redirectToRoute('ex12_listTable');
    }

    #[Route(path: '/list', name: 'ex12_listTable', methods: ['GET'])]
    public function listTable(Request $request, personRepository $personRepository): Response
    {
        $allowedSort = [
            'name' => 'person.name',
            'username' => 'person.username',
            'money' => 'bank.balance'
        ];

        $sortParam = $request->query->get('sort', 'name');
        $sortBy = $allowedSort[$sortParam] ?? 'person.name';

        $orderParam = strtoupper($request->query->get('order', 'ASC'));
        $order = in_array($orderParam, ['ASC', 'DESC'], true) ? $orderParam : 'ASC';

        $nameFilter = $request->query->get('name');
        $rawMinMoney = $request->query->get('min_money');
        $minMoneyFilter = is_numeric($rawMinMoney) ? (int) $rawMinMoney : null;

        $people = $personRepository->findWithAccountFilteredAndSorted(
            $nameFilter,
            $minMoneyFilter,
            $sortBy,
            $order
        );

        return $this->render('listTable.html.twig', [
            'people' => $people,
        ]);
    }
}