<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\DBAL\Connection;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class ex14Controller extends AbstractController {

    public function __construct(private Connection $connection) {}

    #[Route(path:'/new', name:'ex14_newTable')]
    public function newTable(): Response {
        try{
            $sql = 'CREATE TABLE IF NOT EXISTS generic (
                    id INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
                    username VARCHAR(255) NOT NULL);';

            $this->connection->executeStatement($sql);
            $this->addFlash('success', 'tables has been created');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Error while creating table: ' . $e->getMessage());
        }
        return new Response("Success: new table created");
    }

    #[Route(path:'/drop', name:'ex14_dropTable')]
    public function dropTable(): Response {
        try {
            $this->connection->executeStatement('DROP TABLE IF EXISTS generic CASCADE;');
            $this->addFlash('success', 'Table generic deleted successfully');
            return new Response("Success: Table deleted");
        } catch (\Exception $e) {
            $this->addFlash('error', 'Error while deleting table: ' . $e->getMessage());
            return new Response("Error: Table not deleted");
        }
    }

    #[Route(path:'/seed/{number}', name:'ex14_seedTable')]
    public function seedTable(string $number): Response {
        $newusers = (int) $number;
        if ($newusers <= 0) {
            $this->addFlash('error', 'The number must be greater than 0.');
            return new Response('Invalid number', Response::HTTP_BAD_REQUEST);
        }
        $names = ['goofy', 'mickey', 'donald', 'daisy', 'minnie', 'pluto', 'chip', 'dale', 'poo', 'piglet'];
        try {
            $this->connection->beginTransaction();
            for ($i = 0; $i < $newusers; $i++) {
                $base = $names[array_rand($names)];
                $username = $base . '_' . bin2hex(random_bytes(4));

                $this->connection->executeStatement(
                    'INSERT INTO generic (username) VALUES (:u)',
                    ['u' => $username]
                );
            }

            $this->connection->commit();
            $this->addFlash('success', "$newusers random users inserted successfully!");
        } catch (\Exception $e) {
            if ($this->connection->isTransactionActive()) {
                $this->connection->rollBack();
            }
            $this->addFlash('error', 'Error during seed: ' . $e->getMessage());
        }
        return new Response("Seed completed: $newusers random users created.");
    }

    #[Route(path: '/list', name: 'ex14_listTable', methods: ['GET'])]
    public function listTable(): Response {
        $tableExists = true;
        $users = [];

        try {
            $users = $this->connection->fetchAllAssociative('SELECT * FROM generic ORDER BY id ASC');
        } catch (\Exception $e) {
            $tableExists = false;
        }
        return $this->render('listTable.html.twig', [
            'table_exists' => $tableExists,
            'users' => $users,
        ]);
    }


    #[Route(path: '/add', name: 'ex14_addUser', methods: ['GET', 'POST'])]
    public function addUser(Request $request): Response {
        $form = $this->createFormBuilder()
            ->add('username', TextType::class, [
                'label' => 'Username',
                'required' => true,
            ])
            ->add('submit', SubmitType::class, ['label' => 'Submit'])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            try {
                // in questo modo TUTTO viene cosiderato come caratteri letterali
                //  $this->connection->executeStatement(
                //       'INSERT INTO generic (username) VALUES (:username)',
                //        ['username' => $data['username']]
                // );
                $username = $data['username'];
                $sql = "INSERT INTO generic (username) VALUES ('" . $username . "')";
                $this->connection->executeStatement($sql);

                $this->addFlash('success', 'User "' . $username . '" successfully added!');
                return $this->redirectToRoute('ex14_listTable');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Error while inserting user: ' . $e->getMessage());
            }
        }
        return $this->render('add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}