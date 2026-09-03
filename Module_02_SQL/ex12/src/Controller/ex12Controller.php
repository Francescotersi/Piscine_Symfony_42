<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\bankAccountEntity;
use App\Entity\personEntity;
use App\Repository\personRepository;

class ex12Controller extends AbstractController {

    #[Route(path:'/new', name:'ex12_newTable')]
    public function newTable(EntityManagerInterface $em): Response {
        try{
            $schemaTool = new SchemaTool($em);
            $metadata = [
                $em->getClassMetadata(personEntity::class),
                $em->getClassMetadata(bankAccountEntity::class)
            ];
            $schemaTool->updateSchema($metadata);
            return new Response('Successfully created two tables');
        }
        catch (\Exception $e) {
            return new Response('Error while creating two tables: ' . $e->getMessage());
        }
    }

    #[Route(path:"/create", name:"ex12_createPerson")]
    public function createPerson(EntityManagerInterface $em): Response {
        $person = new personEntity();
        $uniq = uniqid();
        $person->setUsername("user_" . $uniq);
        $person->setName("Name " . $uniq);
        $person->setEmail("email_" . $uniq . "@test.com");

        $em->persist($person);
        $em->flush();

        return new Response("Created PersonEntity with ID: " . $person->getId());
    }

    #[Route(path:'/seed', name:'ex12_seedTable')]
    public function seedTable(EntityManagerInterface $em): Response {
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
                $newUser = new personEntity();
                $newBankAccount = new bankAccountEntity();
                $newUser->setUsername($u[0]);
                $newUser->setName($u[1]);
                $newUser->setEmail($u[2]);
                $newBankAccount->setBalance($u[3]);
                $newUser->setBankAccount($newBankAccount);
                $em->persist($newUser);
            }
            $this->addFlash('success', count($users) . ' utenti fittizi inseriti con successo!');

            $em->flush();
            $this->addFlash('success', 'Fake accounts created');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Errore durante il seed: ' . $e->getMessage());
        }
        return $this->redirectToRoute('ex12_listTable');
    }

    #[Route(path:'/list', name:'ex12_listTable', methods:['GET'])]
    public function listTable(Request $request, personRepository $personRepository): Response {
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
        $minMoneyFilter = is_numeric($rawMinMoney) ? (int)$rawMinMoney : null;

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